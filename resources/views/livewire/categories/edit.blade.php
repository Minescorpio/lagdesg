<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Modifier la catégorie') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="btn-primary">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                <span>{{ __('Retour') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Products -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Produits dans la catégorie') }}</p>
                            <p class="stats-value">{{ $category->products->count() }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>

                <!-- Subcategories -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Sous-catégories') }}</p>
                            <p class="stats-value">{{ $category->children->count() }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>

                <!-- Status -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Statut') }}</p>
                            <p class="stats-value">{{ $category->is_active ? __('Actif') : __('Inactif') }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Parent Category -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Catégorie parente') }}</p>
                            <p class="stats-value">{{ $category->parent ? $category->parent->name : __('Aucune') }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-[#2E324A] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <x-label for="name" :value="__('Nom')" />
                            <x-input wire:model="name" id="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-label for="description" :value="__('Description')" />
                            <x-textarea wire:model="description" id="description" class="mt-1 block w-full" rows="3" />
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <x-label for="parent_id" :value="__('Catégorie parente')" />
                            <select wire:model="parent_id" id="parent_id" class="mt-1 block w-full border-gray-700 bg-[#1B1D29] text-white rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Aucune') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="parent_id" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <x-checkbox wire:model="is_active" id="is_active" />
                            <x-label for="is_active" :value="__('Actif')" class="ml-2" />
                            <x-input-error for="is_active" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button type="button" class="mr-4 bg-gray-600 hover:bg-gray-700" wire:click="$dispatch('closeModal')">
                                {{ __('Annuler') }}
                            </x-button>
                            <x-button class="bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Enregistrer') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 