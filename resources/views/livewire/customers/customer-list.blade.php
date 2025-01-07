<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ __('Customers') }}
            </h2>
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
            <a href="{{ route('customers.create') }}" class="btn-primary">
                <i class="fas fa-user-plus mr-2"></i>{{ __('Add Customer') }}
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white shadow-sm rounded-lg">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <div class="flex-1 min-w-0 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="sr-only">{{ __('Search') }}</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="search" wire:model.live.debounce.300ms="search" 
                            class="form-input pl-10" 
                            placeholder="{{ __('Search customers...') }}">
                    </div>
                </div>

                <!-- Loyalty Filter -->
                <div class="sm:w-48">
                    <select wire:model.live="loyaltyFilter" class="form-select">
                        <option value="">{{ __('All Customers') }}</option>
                        <option value="with_points">{{ __('With Points') }}</option>
                        <option value="no_points">{{ __('No Points') }}</option>
                    </select>
                </div>

                <!-- Show Inactive Toggle -->
                <div class="flex items-center">
                    <button type="button" wire:click="$toggle('showInactive')" 
                        class="flex items-center {{ $showInactive ? 'text-blue-600' : 'text-gray-500' }}">
                        <span class="mr-2">
                            <i class="fas {{ $showInactive ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        </span>
                        {{ __('Show Inactive') }}
                    </button>
                </div>
            </div>

            <!-- Per Page -->
            <div class="mt-3 sm:mt-0 sm:ml-4">
                <select wire:model.live="perPage" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="table-th">
                            <button wire:click="sortBy('last_name')" class="group flex items-center space-x-1">
                                <span>{{ __('Name') }}</span>
                                @if($sortField === 'last_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="table-th">{{ __('Contact') }}</th>
                        <th scope="col" class="table-th">{{ __('Loyalty') }}</th>
                        <th scope="col" class="table-th">{{ __('Credit Balance') }}</th>
                        <th scope="col" class="table-th">{{ __('Last Purchase') }}</th>
                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                        <th scope="col" class="table-th">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="table-td">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm">
                                                {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $customer->customer_code }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-td">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $customer->email }}</div>
                                    <div class="text-gray-500">{{ $customer->phone }}</div>
                                </div>
                            </td>
                            <td class="table-td">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-2"></i>
                                    <span class="font-medium">{{ number_format($customer->loyalty_points) }}</span>
                                </div>
                            </td>
                            <td class="table-td">
                                <span class="font-medium {{ $customer->credit_balance > 0 ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ money($customer->credit_balance) }}
                                </span>
                            </td>
                            <td class="table-td">
                                @if($customer->last_purchase_at)
                                    <span class="text-sm text-gray-500">
                                        {{ $customer->last_purchase_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="table-td">
                                <button wire:click="toggleActive({{ $customer->id }})" 
                                    class="status-badge {{ $customer->active ? 'status-badge-success' : 'status-badge-danger' }}">
                                    {{ $customer->active ? __('Active') : __('Inactive') }}
                                </button>
                            </td>
                            <td class="table-td">
                                <div class="flex items-center space-x-3">
                                    <!-- Quick Actions -->
                                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open" 
                                            class="action-button text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" 
                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" 
                                            role="menu" 
                                            aria-orientation="vertical">
                                            <div class="py-1" role="none">
                                                <button wire:click="addPoints({{ $customer->id }})"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left" 
                                                    role="menuitem">
                                                    <i class="fas fa-plus-circle mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                                    {{ __('Add Points') }}
                                                </button>
                                                <button wire:click="addCredit({{ $customer->id }})"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left" 
                                                    role="menuitem">
                                                    <i class="fas fa-money-bill-wave mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                                    {{ __('Add Credit') }}
                                                </button>
                                            </div>
                                            <div class="py-1" role="none">
                                                <a href="{{ route('customers.edit', $customer) }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" 
                                                    role="menuitem">
                                                    <i class="fas fa-edit mr-3 text-gray-400 group-hover:text-gray-500"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                                <button wire:click="delete({{ $customer->id }})"
                                                    wire:confirm="{{ __('Are you sure you want to delete this customer?') }}"
                                                    class="group flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50 w-full text-left" 
                                                    role="menuitem">
                                                    <i class="fas fa-trash mr-3 text-red-400 group-hover:text-red-500"></i>
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <span class="text-xl font-medium">{{ __('No customers found') }}</span>
                                    <span class="text-sm mt-2">{{ __('Try adjusting your search or filters') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Add Points Modal -->
    <x-modal name="add-points" :show="$showAddPoints">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Add Loyalty Points') }}
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="points" class="form-label required">{{ __('Points') }}</label>
                    <input type="number" wire:model="pointsToAdd" id="points" class="form-input" min="0" required>
                    @error('pointsToAdd') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="points_note" class="form-label">{{ __('Note') }}</label>
                    <textarea wire:model="pointsNote" id="points_note" rows="2" class="form-textarea"></textarea>
                    @error('pointsNote') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                    <button type="button" wire:click="$set('showAddPoints', false)"
                        class="btn-secondary w-full">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" wire:click="confirmAddPoints"
                        class="btn-primary w-full">
                        {{ __('Add Points') }}
                    </button>
                </div>
            </div>
        </div>
    </x-modal>

    <!-- Add Credit Modal -->
    <x-modal name="add-credit" :show="$showAddCredit">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Add Credit Balance') }}
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="credit" class="form-label required">{{ __('Amount') }}</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" wire:model="creditToAdd" id="credit" 
                            class="form-input pl-7" step="0.01" min="0" required>
                    </div>
                    @error('creditToAdd') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="credit_note" class="form-label">{{ __('Note') }}</label>
                    <textarea wire:model="creditNote" id="credit_note" rows="2" class="form-textarea"></textarea>
                    @error('creditNote') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                    <button type="button" wire:click="$set('showAddCredit', false)"
                        class="btn-secondary w-full">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" wire:click="confirmAddCredit"
                        class="btn-primary w-full">
                        {{ __('Add Credit') }}
                    </button>
                </div>
            </div>
        </div>
    </x-modal>
</div>

@push('styles')
<style>
    .table-th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .table-td {
        @apply px-6 py-4 whitespace-nowrap text-sm;
    }
    .action-button {
        @apply p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium focus:outline-none;
    }
    .status-badge-success {
        @apply bg-green-100 text-green-800;
    }
    .status-badge-danger {
        @apply bg-red-100 text-red-800;
    }
    .btn-primary {
        @apply inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    .form-label.required::after {
        content: "*";
        @apply text-red-500 ml-1;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-error {
        @apply mt-1 text-sm text-red-600;
    }
</style>
@endpush 