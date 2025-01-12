<div>
    <form wire:submit.prevent="submit" class="space-y-6">
        <!-- Nom -->
        <div>
            <label for="name" class="block text-sm font-medium text-white">{{ __('Nom') }} *</label>
            <div class="mt-1">
                <input wire:model="name" type="text" name="name" id="name" 
                       class="bg-[#1B1D29] text-white block w-full rounded-md border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-white">{{ __('Email') }} *</label>
            <div class="mt-1">
                <input wire:model="email" type="email" name="email" id="email"
                       class="bg-[#1B1D29] text-white block w-full rounded-md border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Sujet -->
        <div>
            <label for="subject" class="block text-sm font-medium text-white">{{ __('Sujet') }} *</label>
            <div class="mt-1">
                <select wire:model="subject" id="subject" name="subject"
                        class="bg-[#1B1D29] text-white block w-full rounded-md border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('Sélectionnez un sujet') }}</option>
                    <option value="question">{{ __('Question générale') }}</option>
                    <option value="order">{{ __('Suivi de commande') }}</option>
                    <option value="return">{{ __('Retour produit') }}</option>
                    <option value="technical">{{ __('Support technique') }}</option>
                    <option value="other">{{ __('Autre') }}</option>
                </select>
            </div>
            @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Message -->
        <div>
            <label for="message" class="block text-sm font-medium text-white">{{ __('Message') }} *</label>
            <div class="mt-1">
                <textarea wire:model="message" id="message" name="message" rows="4"
                          class="bg-[#1B1D29] text-white block w-full rounded-md border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- RGPD -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input wire:model="consent" id="consent" name="consent" type="checkbox"
                       class="h-4 w-4 rounded border-gray-700 bg-[#1B1D29] text-blue-600 focus:ring-blue-500">
            </div>
            <div class="ml-3 text-sm">
                <label for="consent" class="text-gray-300">
                    {{ __('J\'accepte que mes données soient traitées dans le cadre de ma demande de contact') }} *
                </label>
            </div>
        </div>
        @error('consent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <!-- Messages de succès/erreur -->
        @if (session()->has('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bouton d'envoi -->
        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75">
                <span wire:loading.remove>{{ __('Envoyer') }}</span>
                <span wire:loading>{{ __('Envoi en cours...') }}</span>
            </button>
        </div>
    </form>
</div>
