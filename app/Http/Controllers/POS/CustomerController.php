<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->latest()
            ->paginate(10);

        return view('pos.customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('pos.customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('pos.customers.index')
            ->with('success', __('Customer created successfully'));
    }

    public function edit(Customer $customer): View
    {
        return view('pos.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('pos.customers.index')
            ->with('success', __('Customer updated successfully'));
    }

    public function history(Customer $customer): View
    {
        $customer->load(['sales' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('pos.customers.history', compact('customer'));
    }
} 