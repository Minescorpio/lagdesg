<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Ventes') }}
            </h2>
            <a href="{{ route('sales.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nouvelle vente') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="flex flex-col md:flex-row gap-4 mb-6 items-center justify-between">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="search" wire:model.live="search" placeholder="Rechercher des ventes..." class="search-input" />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($dateFilter === 'custom')
                        <div class="flex flex-col md:flex-row gap-4 mb-6">
                            <div class="w-full md:w-48">
                                <label class="form-label">{{ __('Date début') }}</label>
                                <input type="date" wire:model.live="startDate" class="form-input" />
                            </div>
                            <div class="w-full md:w-48">
                                <label class="form-label">{{ __('Date fin') }}</label>
                                <input type="date" wire:model.live="endDate" class="form-input" />
                            </div>
                        </div>
                    @endif

                    <!-- Sales Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ __('N° Reçu') }}</th>
                                    <th class="text-left">{{ __('Client') }}</th>
                                    <th class="text-left">{{ __('Date') }}</th>
                                    <th class="text-right">{{ __('Articles') }}</th>
                                    <th class="text-right">{{ __('Total') }}</th>
                                    <th class="text-center">{{ __('Paiement') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($sales as $sale)
                                    <tr class="hover:bg-[#2E324A] transition-colors duration-200">
                                        <td class="py-4">
                                            <div class="font-medium text-white">{{ $sale->receipt_number }}</div>
                                            @if($sale->notes)
                                                <div class="text-sm text-gray-400">{{ Str::limit($sale->notes, 30) }}</div>
                                            @endif
                                        </td>
                                        <td class="text-gray-400">
                                            @if($sale->customer)
                                                <div class="font-medium text-white">{{ $sale->customer->full_name }}</div>
                                                <div class="text-sm">{{ $sale->customer->email }}</div>
                                            @else
                                                <span class="text-gray-400">{{ __('Client occasionnel') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-gray-400">
                                            <div>{{ $sale->created_at->format('d/m/Y') }}</div>
                                            <div class="text-sm">{{ $sale->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="text-right text-white">{{ $sale->items_count }}</td>
                                        <td class="text-right text-[#FFD700] font-medium">{{ $sale->formatted_total }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ __($sale->payment_method) }}</span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button wire:click="showReceipt({{ $sale->id }})" class="btn-secondary btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </button>
                                                @if($sale->created_at->isToday())
                                                    <button wire:click="confirmSaleDeletion({{ $sale->id }})" class="btn-danger btn-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('Aucune vente trouvée') }}</h3>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: @entangle('showDeleteModal') }"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-[#1F2937] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-600 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-semibold text-white">{{ __('Supprimer la vente') }}</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">{{ __('Êtes-vous sûr de vouloir supprimer cette vente ? Cette action est irréversible.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            wire:click="deleteSale"
                            class="btn-danger w-full sm:w-auto sm:ml-3">
                        {{ __('Supprimer') }}
                    </button>
                    <button type="button"
                            @click="open = false"
                            class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0">
                        {{ __('Annuler') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div x-data="{ open: @entangle('showReceiptModal') }"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-[#1F2937] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button type="button" 
                            @click="open = false"
                            class="rounded-md bg-[#1F2937] text-gray-400 hover:text-gray-300">
                        <span class="sr-only">{{ __('Fermer') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="space-y-4">
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-white">{{ config('app.name') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('Reçu N°') }}: {{ $selectedSale?->receipt_number }}</p>
                            <p class="text-sm text-gray-400">{{ $selectedSale?->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="border-t border-b border-gray-700 py-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left text-gray-400">{{ __('Article') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Qté') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Prix') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($selectedSale?->items ?? [] as $item)
                                        <tr>
                                            <td class="py-2 text-white">{{ $item->product->name }}</td>
                                            <td class="text-right text-white">{{ $item->quantity }}</td>
                                            <td class="text-right text-white">{{ $item->formatted_unit_price }}</td>
                                            <td class="text-right text-[#FFD700]">{{ $item->formatted_total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ __('Sous-total') }}</span>
                                <span class="text-white">{{ $selectedSale?->formatted_subtotal }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ __('TVA') }}</span>
                                <span class="text-white">{{ $selectedSale?->formatted_tax }}</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="text-white">{{ __('Total') }}</span>
                                <span class="text-[#FFD700]">{{ $selectedSale?->formatted_total }}</span>
                            </div>
                            @if($selectedSale?->payment_method === 'cash')
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">{{ __('Montant reçu') }}</span>
                                    <span class="text-white">{{ $selectedSale?->formatted_received_amount }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">{{ __('Monnaie rendue') }}</span>
                                    <span class="text-[#FFD700]">{{ $selectedSale?->formatted_change_amount }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-center text-sm text-gray-400">
                            <p>{{ __('Mode de paiement') }}: {{ __($selectedSale?->payment_method) }}</p>
                            @if($selectedSale?->customer)
                                <p>{{ __('Client') }}: {{ $selectedSale?->customer->full_name }}</p>
                            @endif
                            @if($selectedSale?->notes)
                                <p>{{ __('Notes') }}: {{ $selectedSale?->notes }}</p>
                            @endif
                        </div>

                        <div class="text-center text-xs text-gray-400">
                            <p>{{ __('Merci de votre visite !') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button type="button"
                            wire:click="printReceipt"
                            class="btn-primary w-full">
                        {{ __('Imprimer') }}
                    </button>
                    <button type="button"
                            @click="open = false"
                            class="btn-secondary w-full">
                        {{ __('Fermer') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
