<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Créer une nouvelle catégorie') }}</h2>
                    <button wire:click="cancel" class="btn-secondary">
                        {{ __('Retour à la liste') }}
                    </button>
                </div>

                <form wire:submit="save" class="space-y-8">
                    <!-- Informations de base -->
                    <div class="bg-[#1F2337] rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-white mb-6">{{ __('Informations de base') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-white mb-2">{{ __('Nom de la catégorie') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="name" wire:model="name" class="form-input w-full text-white" required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-white mb-2">{{ __('Description') }}</label>
                                <textarea id="description" wire:model="description" rows="4" class="form-textarea w-full text-white"></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                                    <input type="checkbox" id="active" wire:model="active" class="form-checkbox">
                                    <span class="text-sm text-white">{{ __('Catégorie active') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="cancel" class="btn-secondary">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn-primary">
                            {{ __('Créer la catégorie') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 