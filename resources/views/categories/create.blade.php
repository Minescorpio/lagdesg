<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Nouvelle catégorie') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <form wire:submit="save" class="space-y-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Informations de base') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Nom') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" wire:model="name" class="form-input" required>
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="parent_id" class="form-label">{{ __('Catégorie parente') }}</label>
                                    <select id="parent_id" wire:model="parent_id" class="form-select">
                                        <option value="">{{ __('Aucune') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea id="description" wire:model="description" class="form-input" rows="3"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Options') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="active" wire:model="active" class="form-checkbox">
                                    <label for="active" class="ml-2 text-gray-300">{{ __('Actif') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Créer la catégorie') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 