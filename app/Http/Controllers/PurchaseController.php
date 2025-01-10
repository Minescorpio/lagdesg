<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['customer', 'items'])->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'total_items' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.estimated_resale_price' => 'nullable|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $validated['customer_id'],
            'total_amount' => $validated['total_amount'],
            'total_items' => $validated['total_items'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            $purchase->items()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'purchase_price' => $item['purchase_price'],
                'estimated_resale_price' => $item['estimated_resale_price'] ?? null,
                'condition' => 'used',
            ]);
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['customer', 'items']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sign(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'signature_type' => 'required|in:electronic,printed',
            'electronic_signature' => 'required_if:signature_type,electronic|string',
        ]);

        $purchase->update([
            'signature_type' => $validated['signature_type'],
            'electronic_signature' => $validated['signature_type'] === 'electronic' ? $validated['electronic_signature'] : null,
            'status' => 'completed',
        ]);

        return response()->json(['message' => 'Purchase signed successfully']);
    }

    public function generateDocument(Purchase $purchase)
    {
        $purchase->load(['customer', 'items']);
        
        // Generate PDF logic will be implemented here
        
        return response()->json(['document_url' => $purchase->document_path]);
    }
}
