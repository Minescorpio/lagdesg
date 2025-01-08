<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\NF525AuditLog;
use App\Models\NF525DailyCounter;
use App\Models\NF525FiscalArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class NF525ComplianceService
{
    /**
     * Génère un hash sécurisé pour une vente
     */
    public function generateSaleHash(Sale $sale): string
    {
        $data = $this->prepareSaleData($sale);
        return hash('sha256', json_encode($data));
    }

    /**
     * Prépare les données de vente pour le hachage
     */
    private function prepareSaleData(Sale $sale): array
    {
        return [
            'id' => $sale->id,
            'reference' => $sale->reference,
            'customer_id' => $sale->customer_id,
            'user_id' => $sale->user_id,
            'items' => $sale->items->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total_amount,
            ])->toArray(),
            'subtotal' => $sale->subtotal,
            'tax' => $sale->tax_amount,
            'total' => $sale->total_amount,
            'timestamp' => $sale->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Enregistre un événement dans le journal d'audit
     */
    public function logAuditEvent(string $eventType, $model, array $beforeState = null, array $afterState = null): void
    {
        $lastLog = NF525AuditLog::latest()->first();
        
        $log = new NF525AuditLog([
            'event_type' => $eventType,
            'document_number' => $this->generateDocumentNumber(),
            'user_id' => auth()->id(),
            'before_state' => $beforeState,
            'after_state' => $afterState,
            'event_timestamp' => now(),
            'hash' => $this->generateEventHash($eventType, $beforeState, $afterState),
            'previous_hash' => $lastLog ? $lastLog->hash : null,
        ]);

        $model->auditLogs()->save($log);
    }

    /**
     * Génère un numéro de document unique et séquentiel
     */
    private function generateDocumentNumber(): string
    {
        $prefix = date('Ymd');
        $lastNumber = NF525AuditLog::where('document_number', 'like', $prefix . '%')
            ->orderByDesc('document_number')
            ->first();

        if ($lastNumber) {
            $sequence = intval(substr($lastNumber->document_number, -6)) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Génère un hash pour un événement d'audit
     */
    private function generateEventHash(string $eventType, ?array $beforeState, ?array $afterState): string
    {
        $data = [
            'type' => $eventType,
            'before' => $beforeState,
            'after' => $afterState,
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
            'user' => auth()->id(),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Effectue la clôture journalière
     */
    public function performDailyClosing(Carbon $date): void
    {
        DB::transaction(function () use ($date) {
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();

            // Calcul des totaux journaliers
            $dailyStats = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->selectRaw('
                    COUNT(*) as sales_count,
                    SUM(CASE WHEN status = "voided" THEN 1 ELSE 0 END) as cancelled_sales_count,
                    SUM(total_amount) as total_amount,
                    SUM(tax_amount) as total_tax,
                    SUM(CASE WHEN status = "voided" THEN total_amount ELSE 0 END) as total_cancelled
                ')
                ->first();

            // Création du compteur journalier
            $counter = new NF525DailyCounter([
                'date' => $date->toDateString(),
                'sales_count' => $dailyStats->sales_count,
                'cancelled_sales_count' => $dailyStats->cancelled_sales_count,
                'total_amount' => $dailyStats->total_amount,
                'total_tax' => $dailyStats->total_tax,
                'total_cancelled' => $dailyStats->total_cancelled,
                'daily_hash' => $this->generateDailyHash($date, $dailyStats),
            ]);

            $counter->save();

            // Création de l'archive fiscale si nécessaire
            if ($date->copy()->endOfDay()->isPast()) {
                $this->createFiscalArchive($date);
            }
        });
    }

    /**
     * Génère le hash pour la clôture journalière
     */
    private function generateDailyHash(Carbon $date, $stats): string
    {
        $data = [
            'date' => $date->toDateString(),
            'stats' => $stats->toArray(),
            'timestamp' => now()->format('Y-m-d H:i:s.u'),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Crée l'archive fiscale pour une journée
     */
    private function createFiscalArchive(Carbon $date): void
    {
        $data = $this->prepareFiscalArchiveData($date);
        $fileName = 'fiscal_archive_' . $date->format('Y-m-d') . '.json';
        
        Storage::put('fiscal_archives/' . $fileName, json_encode($data, JSON_PRETTY_PRINT));

        NF525FiscalArchive::create([
            'archive_date' => $date->toDateString(),
            'archive_file_path' => 'fiscal_archives/' . $fileName,
            'archive_hash' => hash('sha256', json_encode($data)),
        ]);
    }

    /**
     * Prépare les données pour l'archive fiscale
     */
    private function prepareFiscalArchiveData(Carbon $date): array
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        return [
            'date' => $date->toDateString(),
            'sales' => Sale::with('items')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->get()
                ->map(fn($sale) => $this->prepareSaleData($sale))
                ->toArray(),
            'audit_logs' => NF525AuditLog::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->get()
                ->toArray(),
            'daily_counter' => NF525DailyCounter::where('date', $date->toDateString())
                ->first()
                ->toArray(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Vérifie l'intégrité des données
     */
    public function verifyDataIntegrity(Carbon $startDate, Carbon $endDate): array
    {
        $issues = [];

        // Vérification des ventes
        Sale::whereBetween('created_at', [$startDate, $endDate])
            ->each(function ($sale) use (&$issues) {
                $currentHash = $this->generateSaleHash($sale);
                if ($currentHash !== $sale->nf525_hash) {
                    $issues[] = "Incohérence détectée pour la vente #{$sale->reference}";
                }
            });

        // Vérification du journal d'audit
        NF525AuditLog::whereBetween('created_at', [$startDate, $endDate])
            ->each(function ($log) use (&$issues) {
                $currentHash = $this->generateEventHash(
                    $log->event_type,
                    $log->before_state,
                    $log->after_state
                );
                if ($currentHash !== $log->hash) {
                    $issues[] = "Incohérence détectée dans le journal d'audit #{$log->document_number}";
                }
            });

        // Vérification des compteurs journaliers
        NF525DailyCounter::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->each(function ($counter) use (&$issues) {
                $currentHash = $this->generateDailyHash($counter->date, $counter);
                if ($currentHash !== $counter->daily_hash) {
                    $issues[] = "Incohérence détectée dans le compteur journalier du {$counter->date}";
                }
            });

        return $issues;
    }
} 