<x-layouts.shop>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informations de contact -->
            <div class="bg-[#2E324A]/80 backdrop-blur-sm p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('Nos Coordonnées') }}</h2>
                <div class="space-y-4 text-gray-300">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-white">{{ __('Adresse') }}</h3>
                            <p>2 RUE DES ANGLAIS</p>
                            <p>14250 TILLY SUR SEULLES</p>
                            <p>FRANCE</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-white">{{ __('Email') }}</h3>
                            <p>contact@lagrottedesgeeks.fr</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-white">{{ __('Informations légales') }}</h3>
                            <p>SIRET: 851 864 918 00012</p>
                            <p>TVA: FR77851864918</p>
                            <p>RCS: Caen A 851 864 918</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-white">{{ __('Horaires de réponse') }}</h3>
                            <p>{{ __('Du lundi au vendredi') }}</p>
                            <p>9h00 - 18h00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="bg-[#2E324A]/80 backdrop-blur-sm p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-white mb-6">{{ __('Contactez-nous') }}</h2>
                <livewire:contact.form />
            </div>
        </div>
    </div>
</x-layouts.shop> 