<?php

namespace App\Console\Commands;

use App\Services\NF525ComplianceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NF525DailyClosing extends Command
{
    protected $signature = 'nf525:daily-closing {date? : Date au format Y-m-d}';
    protected $description = 'Effectue la clôture journalière selon la norme NF525';

    private NF525ComplianceService $complianceService;

    public function __construct(NF525ComplianceService $complianceService)
    {
        parent::__construct();
        $this->complianceService = $complianceService;
    }

    public function handle(): int
    {
        $date = $this->argument('date') ? Carbon::createFromFormat('Y-m-d', $this->argument('date')) : now();

        $this->info("Début de la clôture journalière pour le " . $date->format('d/m/Y'));

        try {
            $this->complianceService->performDailyClosing($date);
            $this->info("Clôture journalière effectuée avec succès");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Erreur lors de la clôture journalière : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 