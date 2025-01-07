<div>
    <div class="min-h-screen bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ __('Settings') }}
                    </h2>
                </div>
            </div>

            <!-- Settings Navigation -->
            <div class="mt-6">
                <div class="sm:hidden">
                    <select wire:model.live="activeTab" class="form-select w-full">
                        <option value="general">{{ __('General') }}</option>
                        <option value="pos">{{ __('POS') }}</option>
                        <option value="invoice">{{ __('Invoice') }}</option>
                        <option value="tax">{{ __('Tax') }}</option>
                        <option value="loyalty">{{ __('Loyalty Program') }}</option>
                        <option value="notifications">{{ __('Notifications') }}</option>
                        <option value="users">{{ __('Users') }}</option>
                        <option value="backup">{{ __('Backup') }}</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button wire:click="$set('activeTab', 'general')" 
                                class="tab-button {{ $activeTab === 'general' ? 'active' : '' }}">
                                <i class="fas fa-cog mr-2"></i>
                                {{ __('General') }}
                            </button>
                            <button wire:click="$set('activeTab', 'pos')" 
                                class="tab-button {{ $activeTab === 'pos' ? 'active' : '' }}">
                                <i class="fas fa-cash-register mr-2"></i>
                                {{ __('POS') }}
                            </button>
                            <button wire:click="$set('activeTab', 'invoice')" 
                                class="tab-button {{ $activeTab === 'invoice' ? 'active' : '' }}">
                                <i class="fas fa-file-invoice mr-2"></i>
                                {{ __('Invoice') }}
                            </button>
                            <button wire:click="$set('activeTab', 'tax')" 
                                class="tab-button {{ $activeTab === 'tax' ? 'active' : '' }}">
                                <i class="fas fa-percent mr-2"></i>
                                {{ __('Tax') }}
                            </button>
                            <button wire:click="$set('activeTab', 'loyalty')" 
                                class="tab-button {{ $activeTab === 'loyalty' ? 'active' : '' }}">
                                <i class="fas fa-award mr-2"></i>
                                {{ __('Loyalty Program') }}
                            </button>
                            <button wire:click="$set('activeTab', 'notifications')" 
                                class="tab-button {{ $activeTab === 'notifications' ? 'active' : '' }}">
                                <i class="fas fa-bell mr-2"></i>
                                {{ __('Notifications') }}
                            </button>
                            <button wire:click="$set('activeTab', 'users')" 
                                class="tab-button {{ $activeTab === 'users' ? 'active' : '' }}">
                                <i class="fas fa-users mr-2"></i>
                                {{ __('Users') }}
                            </button>
                            <button wire:click="$set('activeTab', 'backup')" 
                                class="tab-button {{ $activeTab === 'backup' ? 'active' : '' }}">
                                <i class="fas fa-database mr-2"></i>
                                {{ __('Backup') }}
                            </button>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="mt-6 bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- General Settings -->
                    <div x-show="'{{ $activeTab }}' === 'general'">
                        <form wire:submit="saveGeneralSettings" class="space-y-6">
                            <!-- Business Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Business Information') }}</h3>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="form-label required">{{ __('Business Name') }}</label>
                                        <input type="text" wire:model="settings.business_name" class="form-input" required>
                                        @error('settings.business_name') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label">{{ __('Tax/VAT Number') }}</label>
                                        <input type="text" wire:model="settings.tax_number" class="form-input">
                                        @error('settings.tax_number') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="form-label required">{{ __('Address') }}</label>
                                        <textarea wire:model="settings.address" class="form-textarea" rows="3" required></textarea>
                                        @error('settings.address') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Phone') }}</label>
                                        <input type="tel" wire:model="settings.phone" class="form-input" required>
                                        @error('settings.phone') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Email') }}</label>
                                        <input type="email" wire:model="settings.email" class="form-input" required>
                                        @error('settings.email') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Localization -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Localization') }}</h3>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="form-label required">{{ __('Default Language') }}</label>
                                        <select wire:model="settings.default_language" class="form-select" required>
                                            <option value="en">English</option>
                                            <option value="fr">Français</option>
                                            <option value="es">Español</option>
                                            <option value="de">Deutsch</option>
                                        </select>
                                        @error('settings.default_language') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Default Currency') }}</label>
                                        <select wire:model="settings.default_currency" class="form-select" required>
                                            <option value="USD">USD ($)</option>
                                            <option value="EUR">EUR (€)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="JPY">JPY (¥)</option>
                                        </select>
                                        @error('settings.default_currency') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Timezone') }}</label>
                                        <select wire:model="settings.timezone" class="form-select" required>
                                            @foreach($timezones as $tz)
                                                <option value="{{ $tz }}">{{ $tz }}</option>
                                            @endforeach
                                        </select>
                                        @error('settings.timezone') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Date Format') }}</label>
                                        <select wire:model="settings.date_format" class="form-select" required>
                                            <option value="Y-m-d">YYYY-MM-DD</option>
                                            <option value="d/m/Y">DD/MM/YYYY</option>
                                            <option value="m/d/Y">MM/DD/YYYY</option>
                                            <option value="d.m.Y">DD.MM.YYYY</option>
                                        </select>
                                        @error('settings.date_format') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Logo -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Logo') }}</h3>
                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0">
                                        @if($logo)
                                            <img src="{{ $logo->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-lg">
                                        @elseif($settings['logo'])
                                            <img src="{{ Storage::url($settings['logo']) }}" class="h-16 w-16 object-cover rounded-lg">
                                        @else
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" wire:model="logo" class="hidden" id="logo-upload" accept="image/*">
                                        <label for="logo-upload" class="btn-secondary cursor-pointer">
                                            <i class="fas fa-upload mr-2"></i>{{ __('Upload Logo') }}
                                        </label>
                                        @error('logo') <span class="form-error block mt-1">{{ $message }}</span> @enderror
                                        <p class="text-sm text-gray-500 mt-1">{{ __('Recommended size: 200x200px. Max file size: 2MB.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="pt-6 border-t border-gray-200">
                                <div class="flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save mr-2"></i>{{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- POS Settings -->
                    <div x-show="'{{ $activeTab }}' === 'pos'">
                        <form wire:submit="savePosSettings" class="space-y-6">
                            <!-- Receipt Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Receipt Settings') }}</h3>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="form-label required">{{ __('Receipt Prefix') }}</label>
                                        <input type="text" wire:model="settings.receipt_prefix" class="form-input" required>
                                        @error('settings.receipt_prefix') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Receipt Number Length') }}</label>
                                        <input type="number" wire:model="settings.receipt_number_length" class="form-input" min="4" max="10" required>
                                        @error('settings.receipt_number_length') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="form-label">{{ __('Receipt Footer Text') }}</label>
                                        <textarea wire:model="settings.receipt_footer" class="form-textarea" rows="3"></textarea>
                                        @error('settings.receipt_footer') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Display Settings -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Display Settings') }}</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="settings.show_stock_alert" class="form-checkbox" id="show_stock_alert">
                                        <label for="show_stock_alert" class="ml-3 text-sm text-gray-700">
                                            {{ __('Show low stock alerts on POS screen') }}
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="settings.require_customer" class="form-checkbox" id="require_customer">
                                        <label for="require_customer" class="ml-3 text-sm text-gray-700">
                                            {{ __('Require customer information for each sale') }}
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="settings.print_receipt_automatically" class="form-checkbox" id="print_receipt_automatically">
                                        <label for="print_receipt_automatically" class="ml-3 text-sm text-gray-700">
                                            {{ __('Print receipt automatically after sale') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Default Values -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Default Values') }}</h3>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="form-label required">{{ __('Default Payment Method') }}</label>
                                        <select wire:model="settings.default_payment_method" class="form-select" required>
                                            <option value="cash">{{ __('Cash') }}</option>
                                            <option value="card">{{ __('Card') }}</option>
                                        </select>
                                        @error('settings.default_payment_method') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="form-label required">{{ __('Items Per Page') }}</label>
                                        <select wire:model="settings.items_per_page" class="form-select" required>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                        </select>
                                        @error('settings.items_per_page') <span class="form-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="pt-6 border-t border-gray-200">
                                <div class="flex justify-end">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-save mr-2"></i>{{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Invoice Settings -->
                    <div x-show="'{{ $activeTab }}' === 'invoice'">
                        <!-- Invoice settings content -->
                    </div>

                    <!-- Tax Settings -->
                    <div x-show="'{{ $activeTab }}' === 'tax'">
                        <!-- Tax settings content -->
                    </div>

                    <!-- Loyalty Program Settings -->
                    <div x-show="'{{ $activeTab }}' === 'loyalty'">
                        <!-- Loyalty program settings content -->
                    </div>

                    <!-- Notification Settings -->
                    <div x-show="'{{ $activeTab }}' === 'notifications'">
                        <!-- Notification settings content -->
                    </div>

                    <!-- User Settings -->
                    <div x-show="'{{ $activeTab }}' === 'users'">
                        <!-- User settings content -->
                    </div>

                    <!-- Backup Settings -->
                    <div x-show="'{{ $activeTab }}' === 'backup'">
                        <!-- Backup settings content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .tab-button {
        @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm;
    }
    .tab-button.active {
        @apply border-blue-500 text-blue-600;
    }
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
    .form-checkbox {
        @apply h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded;
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