<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Fournisseurs') }}
            </h2>
            <a href="{{ route('fournisseurs.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nouveau Fournisseur') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Content -->
            <div class="bg-[#1F2937] rounded-lg shadow-sm">
                <div class="p-6">
                    <!-- Search -->
                    <div class="mb-6">
                        <div class="relative">
                            <input type="search" placeholder="{{ __('Search fournisseurs...') }}" class="block w-full pl-16 pr-4 py-4 text-lg border border-gray-600 rounded-2xl leading-6 bg-[#374151] text-white placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-10 py-8 text-left text-white">{{ __('Name') }}</th>
                                    <th class="px-10 py-8 text-left text-white">{{ __('Email') }}</th>
                                    <th class="px-10 py-8 text-left text-white">{{ __('Phone') }}</th>
                                    <th class="px-10 py-8 text-left text-white">{{ __('Address') }}</th>
                                    <th class="px-10 py-8 text-right text-white">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($fournisseurs as $fournisseur)
                                    <tr class="hover:bg-[#2E324A] transition-colors duration-200">
                                        <td class="px-10 py-6">
                                            <div class="font-medium text-white">{{ $fournisseur->nom }}</div>
                                        </td>
                                        <td class="px-10 py-6 text-white">{{ $fournisseur->email }}</td>
                                        <td class="px-10 py-6 text-white">{{ $fournisseur->telephone }}</td>
                                        <td class="px-10 py-6 text-white">{{ $fournisseur->adresse }}</td>
                                        <td class="px-10 py-6 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-sm text-red-500 hover:text-red-700" onclick="return confirm('{{ __('Are you sure you want to delete this supplier?') }}')">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('No suppliers found') }}</h3>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .btn-primary {
            @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
        }
        
        .btn-sm {
            @apply p-2 rounded-full text-white hover:bg-[#2E324A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150;
        }
    </style>
    @endpush
</x-app-layout> 