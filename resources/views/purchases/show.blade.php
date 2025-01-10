<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Détails du Rachat') }}
            </h2>
            <div class="flex space-x-4">
                <button type="button" id="printDocument" class="btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    {{ __('Imprimer le Document') }}
                </button>
                @if($purchase->status !== 'completed')
                    <button type="button" id="signDocument" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        {{ __('Signer le Document') }}
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#2E324A] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Customer Information -->
                    <div class="bg-[#373B56] p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-white mb-4">{{ __('Information Client') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Nom') }}</p>
                                <p class="text-white">{{ $purchase->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Email') }}</p>
                                <p class="text-white">{{ $purchase->customer->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Téléphone') }}</p>
                                <p class="text-white">{{ $purchase->customer->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Adresse') }}</p>
                                <p class="text-white">{{ $purchase->customer->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Items -->
                    <div class="bg-[#373B56] p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-medium text-white mb-4">{{ __('Articles Rachetés') }}</h3>
                        <div class="space-y-4">
                            @foreach($purchase->items as $item)
                                <div class="bg-[#2E324A] p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-400">{{ __('Nom de l\'Article') }}</p>
                                            <p class="text-white">{{ $item->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-400">{{ __('Prix de Rachat') }}</p>
                                            <p class="text-white">{{ number_format($item->purchase_price, 2) }} €</p>
                                        </div>
                                        @if($item->estimated_resale_price)
                                            <div>
                                                <p class="text-sm text-gray-400">{{ __('Prix de Revente Estimé') }}</p>
                                                <p class="text-white">{{ number_format($item->estimated_resale_price, 2) }} €</p>
                                            </div>
                                        @endif
                                        @if($item->description)
                                            <div class="md:col-span-2">
                                                <p class="text-sm text-gray-400">{{ __('Description') }}</p>
                                                <p class="text-white">{{ $item->description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Purchase Summary -->
                    <div class="bg-[#373B56] p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-white mb-4">{{ __('Résumé du Rachat') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Nombre d\'Articles') }}</p>
                                <p class="text-white">{{ $purchase->total_items }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">{{ __('Montant Total') }}</p>
                                <p class="text-white">{{ number_format($purchase->total_amount, 2) }} €</p>
                            </div>
                            @if($purchase->notes)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-400">{{ __('Notes') }}</p>
                                    <p class="text-white">{{ $purchase->notes }}</p>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <p class="text-sm text-white">{{ __('Status') }}</p>
                                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($purchase->status === 'completed') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($purchase->status === 'completed')
                                        {{ __('Terminé') }}
                                    @else
                                        {{ __('En attente') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Modal -->
    <div id="signatureModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-[#2E324A] rounded-lg p-6 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-white">{{ __('Signer le Document de Rachat') }}</h3>
                <button type="button" class="text-gray-400 hover:text-white" onclick="closeSignatureModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div class="border-2 border-gray-600 rounded-lg">
                    <canvas id="signaturePad" class="w-full bg-white rounded-lg"></canvas>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" class="btn-secondary" onclick="clearSignature()">
                        {{ __('Effacer') }}
                    </button>
                    <button type="button" class="btn-primary" onclick="saveSignature()">
                        {{ __('Enregistrer la Signature') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        let signaturePad;
        const modal = document.getElementById('signatureModal');
        
        document.getElementById('signDocument')?.addEventListener('click', openSignatureModal);
        document.getElementById('printDocument').addEventListener('click', generateDocument);

        function openSignatureModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            const canvas = document.getElementById('signaturePad');
            canvas.width = canvas.offsetWidth;
            canvas.height = 200;
            
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'white'
            });
        }

        function closeSignatureModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function clearSignature() {
            signaturePad.clear();
        }

        function saveSignature() {
            if (signaturePad.isEmpty()) {
                alert('{{ __("Veuillez fournir une signature.") }}');
                return;
            }

            const signatureData = signaturePad.toDataURL();
            
            fetch('{{ route("purchases.sign", $purchase) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    signature_type: 'electronic',
                    electronic_signature: signatureData
                })
            })
            .then(response => response.json())
            .then(data => {
                closeSignatureModal();
                window.location.reload();
            })
            .catch(error => {
                alert('{{ __("Une erreur est survenue lors de l\'enregistrement de la signature.") }}');
                console.error('Error:', error);
            });
        }

        function generateDocument() {
            window.location.href = '{{ route("purchases.document", $purchase) }}';
        }
    </script>
    @endpush
</x-app-layout> 