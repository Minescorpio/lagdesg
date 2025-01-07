<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['customer', 'user'])
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::where('active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::where('active', true)
            ->orderBy('last_name')
            ->get();

        $loyaltyPrograms = LoyaltyProgram::where('active', true)
            ->get();

        return view('sales.create', compact('products', 'customers', 'loyaltyPrograms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,loyalty_points,mixed',
            'payment_details' => 'nullable|array',
            'loyalty_points_used' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'payment_method' => $validated['payment_method'],
                'payment_details' => $validated['payment_details'],
                'loyalty_points_used' => $validated['loyalty_points_used'] ?? 0,
                'notes' => $validated['notes'],
            ]);

            // Process items
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemTax = ($itemTotal - $itemDiscount) * ($product->vat_rate / 100);

                $subtotal += $itemTotal;
                $taxAmount += $itemTax;
                $discountAmount += $itemDiscount;

                // Create sale item
                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $itemDiscount,
                    'tax_rate' => $product->vat_rate,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemTotal - $itemDiscount + $itemTax,
                    'product_data' => $product->only([
                        'name', 'barcode', 'description', 'vat_rate'
                    ]),
                ]);

                // Update stock if product is tracked
                if ($product->track_stock) {
                    $product->decrement('current_stock', $item['quantity']);
                }
            }

            // Update sale totals
            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $subtotal + $taxAmount - $discountAmount,
                'completed_at' => now(),
                'payment_status' => 'paid',
            ]);

            // Process loyalty points if customer exists
            if ($sale->customer_id) {
                $this->processLoyaltyPoints($sale);
            }

            return redirect()->route('sales.show', $sale)
                ->with('success', __('Sale completed successfully'));
        });
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'items.product', 'user']);
        return view('sales.show', compact('sale'));
    }

    public function void(Sale $sale): RedirectResponse
    {
        if ($sale->payment_status === 'cancelled') {
            return back()->with('error', __('Sale is already cancelled'));
        }

        DB::transaction(function () use ($sale) {
            // Restore stock for tracked products
            foreach ($sale->items as $item) {
                if ($item->product->track_stock) {
                    $item->product->increment('current_stock', $item->quantity);
                }
            }

            // Restore customer loyalty points if used
            if ($sale->customer && $sale->loyalty_points_used > 0) {
                $sale->customer->increment('loyalty_points', $sale->loyalty_points_used);
            }

            // Remove earned points
            if ($sale->customer && $sale->loyalty_points_earned > 0) {
                $sale->customer->decrement('loyalty_points', $sale->loyalty_points_earned);
            }

            $sale->update([
                'payment_status' => 'cancelled',
                'notes' => $sale->notes . "\nVoided at " . now(),
            ]);
        });

        return redirect()->route('sales.index')
            ->with('success', __('Sale voided successfully'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'user']);
        return view('sales.receipt', compact('sale'));
    }

    protected function generateInvoiceNumber(): string
    {
        $prefix = date('Ym');
        $lastSale = Sale::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastSale) {
            $sequence = intval(substr($lastSale->invoice_number, -4)) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    protected function processLoyaltyPoints(Sale $sale): void
    {
        $customer = $sale->customer;
        
        // Deduct used points
        if ($sale->loyalty_points_used > 0) {
            if ($customer->loyalty_points < $sale->loyalty_points_used) {
                throw new \Exception(__('Insufficient loyalty points'));
            }
            $customer->decrement('loyalty_points', $sale->loyalty_points_used);
        }

        // Calculate and add earned points
        $activeProgram = LoyaltyProgram::where('active', true)
            ->where('type', 'points')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->first();

        if ($activeProgram && $sale->total_amount >= $activeProgram->minimum_purchase) {
            $pointsEarned = floor($sale->total_amount * $activeProgram->points_per_currency);
            $customer->increment('loyalty_points', $pointsEarned);
            $sale->update(['loyalty_points_earned' => $pointsEarned]);
        }

        // Update last purchase date
        $customer->update(['last_purchase_at' => now()]);
    }
} 