<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoyaltyProgramController extends Controller
{
    public function index(): View
    {
        $programs = LoyaltyProgram::latest()
            ->paginate(10);

        return view('loyalty-programs.index', compact('programs'));
    }

    public function create(): View
    {
        return view('loyalty-programs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:points,percentage,fixed_amount',
            'points_per_currency' => 'nullable|required_if:type,points|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'reward_value' => 'required|numeric|min:0',
            'points_required' => 'nullable|required_if:type,points|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'active' => 'boolean',
            'conditions' => 'nullable|array',
        ]);

        LoyaltyProgram::create($validated);

        return redirect()->route('loyalty-programs.index')
            ->with('success', __('Loyalty program created successfully'));
    }

    public function show(LoyaltyProgram $loyaltyProgram): View
    {
        return view('loyalty-programs.show', compact('loyaltyProgram'));
    }

    public function edit(LoyaltyProgram $loyaltyProgram): View
    {
        return view('loyalty-programs.edit', compact('loyaltyProgram'));
    }

    public function update(Request $request, LoyaltyProgram $loyaltyProgram): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:points,percentage,fixed_amount',
            'points_per_currency' => 'nullable|required_if:type,points|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'reward_value' => 'required|numeric|min:0',
            'points_required' => 'nullable|required_if:type,points|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'active' => 'boolean',
            'conditions' => 'nullable|array',
        ]);

        $loyaltyProgram->update($validated);

        return redirect()->route('loyalty-programs.index')
            ->with('success', __('Loyalty program updated successfully'));
    }

    public function destroy(LoyaltyProgram $loyaltyProgram): RedirectResponse
    {
        $loyaltyProgram->delete();

        return redirect()->route('loyalty-programs.index')
            ->with('success', __('Loyalty program deleted successfully'));
    }

    public function calculateReward(Request $request, LoyaltyProgram $loyaltyProgram)
    {
        $validated = $request->validate([
            'purchase_amount' => 'required|numeric|min:0',
            'points_to_use' => 'nullable|integer|min:0',
        ]);

        $reward = 0;
        
        switch ($loyaltyProgram->type) {
            case 'points':
                if ($validated['purchase_amount'] >= $loyaltyProgram->minimum_purchase) {
                    $reward = floor($validated['purchase_amount'] * $loyaltyProgram->points_per_currency);
                }
                break;
            case 'percentage':
                if ($validated['purchase_amount'] >= $loyaltyProgram->minimum_purchase) {
                    $reward = ($validated['purchase_amount'] * $loyaltyProgram->reward_value) / 100;
                }
                break;
            case 'fixed_amount':
                if ($validated['purchase_amount'] >= $loyaltyProgram->minimum_purchase) {
                    $reward = $loyaltyProgram->reward_value;
                }
                break;
        }

        return response()->json([
            'reward' => $reward,
            'message' => __('Reward calculated successfully')
        ]);
    }
} 