<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                Catégories
            </h2>
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
            <a href="{{ route('categories.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Ajouter une catégorie
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white shadow-sm rounded-lg">
        <!-- Filtres -->
        <div class="p-4 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <div class="flex-1 min-w-0 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                <!-- Recherche -->
                <div class="flex-1">
                    <label for="search" class="sr-only">Rechercher</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="search" wire:model.live.debounce.300ms="search" 
                            class="form-input pl-10" 
                            placeholder="Rechercher des catégories...">
                    </div>
                </div>

                <!-- Afficher les inactifs -->
                <div class="flex items-center">
                    <button type="button" wire:click="$toggle('showInactive')" 
                        class="flex items-center {{ $showInactive ? 'text-blue-600' : 'text-gray-500' }}">
                        <i class="fas fa-eye{{ $showInactive ? '' : '-slash' }} mr-2"></i>
                        Afficher les inactifs
                    </button>
                </div>
            </div>
        </div>

        <!-- Table des catégories -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="table-th">
                            <button type="button" wire:click="sortBy('name')" class="group inline-flex items-center space-x-1">
                                <span>Nom</span>
                                @if ($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-gray-400 group-hover:text-gray-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="table-th">Description</th>
                        <th scope="col" class="table-th">Produits</th>
                        <th scope="col" class="table-th">Statut</th>
                        <th scope="col" class="table-th">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="table-td font-medium text-white">
                                {{ $category->name }}
                            </td>
                            <td class="table-td">
                                {{ Str::limit($category->description, 50) }}
                            </td>
                            <td class="table-td">
                                {{ $category->products_count }}
                            </td>
                            <td class="table-td">
                                <button wire:click="toggleActive({{ $category->id }})" 
                                    class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                                    {{ $category->is_active 
                                        ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                        : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ $category->is_active ? __('Active') : __('Inactive') }}
                                </button>
                            </td>
                            <td class="table-td">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('categories.edit', $category) }}" 
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $category->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-td text-center">
                                Aucune catégorie trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div x-data="{ show: @entangle('showDeleteModal') }"
         x-show="show"
         x-cloak
         class="fixed z-50 inset-0 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Supprimer la catégorie
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            wire:click="deleteCategory"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer
                    </button>
                    <button type="button"
                            wire:click="$set('showDeleteModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
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
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 