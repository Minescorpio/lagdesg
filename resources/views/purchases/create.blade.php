<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Nouveau Rachat de Produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#2E324A] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="bg-[#373B56] p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Information Client') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label for="customer_name" value="{{ __('Nom') }}" class="text-white" />
                                    <x-input id="customer_name" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-label for="customer_email" value="{{ __('Email') }}" class="text-white" />
                                    <x-input id="customer_email" type="email" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-label for="customer_phone" value="{{ __('Téléphone') }}" class="text-white" />
                                    <x-input id="customer_phone" type="tel" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-label for="customer_address" value="{{ __('Adresse') }}" class="text-white" />
                                    <x-input id="customer_address" type="text" class="mt-1 block w-full" required />
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Items -->
                        <div class="bg-[#373B56] p-6 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-white">{{ __('Articles à Racheter') }}</h3>
                                <button type="button" id="addItem" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    {{ __('Ajouter un Article') }}
                                </button>
                            </div>
                            
                            <div id="itemsContainer" class="space-y-4">
                                <!-- Items will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Purchase Summary -->
                        <div class="bg-[#373B56] p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Résumé du Rachat') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-label for="total_items" value="{{ __('Nombre d\'Articles') }}" class="text-white" />
                                    <x-input id="total_items" type="number" class="mt-1 block w-full" readonly />
                                </div>
                                <div>
                                    <x-label for="total_amount" value="{{ __('Montant Total') }}" class="text-white" />
                                    <x-input id="total_amount" type="number" step="0.01" class="mt-1 block w-full" readonly />
                                </div>
                                <div class="md:col-span-2">
                                    <x-label for="notes" value="{{ __('Notes') }}" class="text-white" />
                                    <textarea id="notes" name="notes" rows="3" class="form-textarea mt-1 block w-full"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('purchases.index') }}" class="btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-primary">
                                {{ __('Créer le Rachat') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 0;
            const itemsContainer = document.getElementById('itemsContainer');
            const totalItemsInput = document.getElementById('total_items');
            const totalAmountInput = document.getElementById('total_amount');

            function updateTotals() {
                const items = itemsContainer.querySelectorAll('.purchase-item');
                let totalItems = items.length;
                let totalAmount = 0;

                items.forEach(item => {
                    const priceInput = item.querySelector('[name^="items"][name$="[purchase_price]"]');
                    if (priceInput && priceInput.value) {
                        totalAmount += parseFloat(priceInput.value);
                    }
                });

                totalItemsInput.value = totalItems;
                totalAmountInput.value = totalAmount.toFixed(2);
            }

            document.getElementById('addItem').addEventListener('click', function() {
                const itemHtml = `
                    <div class="purchase-item bg-[#2E324A] p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-white font-medium">{{ __('Article') }} ${itemCount + 1}</h4>
                            <button type="button" class="text-red-400 hover:text-red-300" onclick="this.closest('.purchase-item').remove(); updateTotals();">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label value="{{ __('Nom de l\'Article') }}" class="text-white" />
                                <x-input type="text" name="items[${itemCount}][name]" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-label value="{{ __('Prix de Rachat') }}" class="text-white" />
                                <x-input type="number" step="0.01" name="items[${itemCount}][purchase_price]" class="mt-1 block w-full" required onchange="updateTotals()" />
                            </div>
                            <div>
                                <x-label value="{{ __('Prix de Revente Estimé') }}" class="text-white" />
                                <x-input type="number" step="0.01" name="items[${itemCount}][estimated_resale_price]" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <x-label value="{{ __('Description') }}" class="text-white" />
                                <textarea name="items[${itemCount}][description]" class="form-textarea mt-1 block w-full" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                `;

                itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                itemCount++;
                updateTotals();
            });

            // Add first item automatically
            document.getElementById('addItem').click();
        });
    </script>
    @endpush
</x-app-layout> 