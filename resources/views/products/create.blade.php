<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Créer un nouveau produit') }}</h2>
                    <a href="{{ route('products.index') }}" class="btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Informations de base -->
                    <div class="bg-[#1F2337] rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-white mb-6">{{ __('Informations de base') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-white mb-2">{{ __('Nom du produit') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="form-input w-full text-white" required>
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-white mb-2">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="4" class="form-textarea w-full text-white"></textarea>
                            </div>

                            <!-- Prix -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-white mb-2">{{ __('Prix') }} <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="price" id="price" class="form-input w-full pr-8 text-white" required>
                                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white">€</span>
                                </div>
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-white mb-2">{{ __('Catégorie') }} <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" class="form-select w-full text-white" required>
                                    <option value="" class="text-white">{{ __('Sélectionner une catégorie') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="text-white">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fournisseur -->
                            <div>
                                <label for="fournisseur_id" class="block text-sm font-medium text-white mb-2">{{ __('Fournisseur') }}</label>
                                <select name="fournisseur_id" id="fournisseur_id" class="form-select w-full text-white" wire:model="fournisseur_id">
                                    <option value="" class="text-white">{{ __('Sélectionner un fournisseur') }}</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" class="text-white">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Gestion du stock -->
                    <div class="bg-[#1F2337] rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-white mb-6">{{ __('Gestion du stock') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantité -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-white mb-2">{{ __('Quantité en stock') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-input w-full text-white" required>
                            </div>

                            <!-- Seuil d'alerte -->
                            <div>
                                <label for="alert_threshold" class="block text-sm font-medium text-white mb-2">{{ __('Seuil d\'alerte') }}</label>
                                <input type="number" name="alert_threshold" id="alert_threshold" class="form-input w-full text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Statut et options -->
                    <div class="bg-[#1F2337] rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-white mb-6">{{ __('Statut et options') }}</h3>
                        <div class="space-y-4">
                            <!-- Statut -->
                            <div>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-checkbox" checked>
                                    <span class="text-sm text-white">{{ __('Produit actif') }}</span>
                                </label>
                            </div>

                            <!-- Featured -->
                            <div>
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="is_featured" id="is_featured" class="form-checkbox">
                                    <span class="text-sm text-white">{{ __('Produit mis en avant') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="bg-[#1F2337] rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-white mb-6">{{ __('Images') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-center w-full">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-[#2E324A] hover:bg-[#363B56] transition-colors duration-150">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-white">{{ __('Cliquez ou glissez-déposez') }}</p>
                                        <p class="text-xs text-white">PNG, JPG, GIF (MAX. 2MB)</p>
                                    </div>
                                    <input type="file" name="image" id="image" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('products.index') }}" class="btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Créer le produit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 