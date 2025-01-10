<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Nouveau Fournisseur') }}
            </h2>
            <a href="{{ route('fournisseurs.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#1F2937] rounded-lg shadow-sm">
                <form action="{{ route('fournisseurs.store') }}" method="POST" class="p-6">
                    @csrf
                    
                    <!-- Informations du fournisseur -->
                    <div class="space-y-6">
                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-white">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white">
                                {{ __('Email') }}
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-white">
                                {{ __('Phone') }}
                            </label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-white">
                                {{ __('Address') }}
                            </label>
                            <textarea name="adresse" id="adresse" rows="3"
                                class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('fournisseurs.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Create Supplier') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
        }
        
        .btn-secondary {
            @apply inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-white bg-[#374151] hover:bg-[#2E324A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500;
        }
    </style>
    @endpush
</x-app-layout> 