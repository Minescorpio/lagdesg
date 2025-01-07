<?php

namespace App\Http\Controllers;

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
            ->latest()
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        // Generate unique customer code
        $validated['customer_code'] = $this->generateCustomerCode();

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', __('Customer created successfully'));
    }

    public function show(Customer $customer): View
    {
        $customer->load(['sales' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', __('Customer updated successfully'));
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->sales()->exists()) {
            return back()->with('error', __('Cannot delete customer with associated sales.'));
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', __('Customer deleted successfully'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        return Customer::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('customer_code', 'like', "%{$query}%");
            })
            ->take(10)
            ->get();
    }

    public function addLoyaltyPoints(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $customer->increment('loyalty_points', $validated['points']);

        return back()->with('success', __('Loyalty points added successfully'));
    }

    public function addCredit(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $customer->increment('credit_balance', $validated['amount']);

        return back()->with('success', __('Credit added successfully'));
    }

    protected function generateCustomerCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Customer::where('customer_code', $code)->exists());

        return $code;
    }
} 