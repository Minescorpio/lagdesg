<div>
    <form wire:submit="save" class="space-y-8">
        <div class="bg-white shadow-sm rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $customerId ? __('Edit Customer') : __('New Customer') }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('customers.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ $customerId ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Personal Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Personal Information') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="form-label required">{{ __('First Name') }}</label>
                                <input type="text" wire:model="first_name" id="first_name" class="form-input" required>
                                @error('first_name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="form-label required">{{ __('Last Name') }}</label>
                                <input type="text" wire:model="last_name" id="last_name" class="form-input" required>
                                @error('last_name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" wire:model="email" id="email" class="form-input">
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <input type="tel" wire:model="phone" id="phone" class="form-input">
                                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="form-label">{{ __('Birth Date') }}</label>
                                <input type="date" wire:model="birth_date" id="birth_date" class="form-input">
                                @error('birth_date') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Customer Code -->
                            <div>
                                <label for="customer_code" class="form-label">{{ __('Customer Code') }}</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" wire:model="customer_code" id="customer_code" 
                                        class="form-input flex-1 rounded-r-none">
                                    <button type="button" wire:click="generateCustomerCode"
                                        class="relative -ml-px inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <i class="fas fa-random mr-2"></i>
                                        {{ __('Generate') }}
                                    </button>
                                </div>
                                @error('customer_code') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Address') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="address" class="form-label">{{ __('Street Address') }}</label>
                                <input type="text" wire:model="address" id="address" class="form-input">
                                @error('address') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="form-label">{{ __('City') }}</label>
                                <input type="text" wire:model="city" id="city" class="form-input">
                                @error('city') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code" class="form-label">{{ __('Postal Code') }}</label>
                                <input type="text" wire:model="postal_code" id="postal_code" class="form-input">
                                @error('postal_code') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Country -->
                            <div class="sm:col-span-2">
                                <label for="country" class="form-label">{{ __('Country') }}</label>
                                <select wire:model="country" id="country" class="form-select">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach($countries as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty & Credit -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Loyalty & Credit') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Loyalty Points -->
                            <div>
                                <label for="loyalty_points" class="form-label">{{ __('Loyalty Points') }}</label>
                                <input type="number" wire:model="loyalty_points" id="loyalty_points"
                                    class="form-input" min="0" step="1">
                                @error('loyalty_points') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Credit Balance -->
                            <div>
                                <label for="credit_balance" class="form-label">{{ __('Credit Balance') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" wire:model="credit_balance" id="credit_balance"
                                        class="form-input pl-7" step="0.01" min="0">
                                </div>
                                @error('credit_balance') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Information') }}</h4>
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Notes -->
                            <div>
                                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                <textarea wire:model="notes" id="notes" rows="3" class="form-textarea"></textarea>
                                @error('notes') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('active')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $active ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Active') }}</span>
                                    <span class="text-sm text-gray-500">{{ __('Customer can make purchases and earn points') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    .form-label.required::after {
        content: "*";
        @apply text-red-500 ml-1;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-error {
        @apply mt-1 text-sm text-red-600;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 