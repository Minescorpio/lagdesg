<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-white">{{ __('Clients') }}</h2>
        <a href="{{ route('customers.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>{{ __('NOUVEAU CLIENT') }}
        </a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="search" wire:model.live.debounce.300ms="search"
                class="form-input block w-full pl-10 text-white bg-[#374151] border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="{{ __('Rechercher des clients...') }}">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="py-4 text-left text-white">{{ __('Nom') }}</th>
                    <th class="py-4 text-left text-white">{{ __('Contact') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Téléphone') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Email') }}</th>
                    <th class="py-4 text-center text-white">{{ __('Statut') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($customers as $customer)
                    <tr class="hover:bg-[#2E324A] transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-sm">
                                        {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-white">{{ $customer->full_name }}</div>
                                    @if($customer->company)
                                        <div class="text-sm text-gray-500">{{ $customer->company }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $customer->email }}</div>
                            <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-white">{{ $customer->phone }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-white">{{ $customer->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleActive({{ $customer->id }})" 
                                class="status-badge {{ $customer->active ? 'status-badge-success' : 'status-badge-danger' }}">
                                {{ $customer->active ? __('Actif') : __('Inactif') }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('customers.edit', $customer) }}" 
                                    class="action-button">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button wire:click="delete({{ $customer->id }})"
                                    wire:confirm="{{ __('Êtes-vous sûr de vouloir supprimer ce client ?') }}"
                                    class="action-button text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl mb-4 text-white"></i>
                                <span class="text-xl font-medium text-white">{{ __('Aucun client trouvé') }}</span>
                                <span class="text-sm mt-2 text-white">{{ __('Essayez d\'ajuster votre recherche') }}</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    @endif
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