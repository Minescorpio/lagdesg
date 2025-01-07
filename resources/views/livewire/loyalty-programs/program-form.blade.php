<div>
    <form wire:submit="save" class="space-y-8">
        <div class="bg-white shadow-sm rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $programId ? __('Edit Program') : __('New Program') }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('loyalty-programs.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ $programId ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div class="sm:col-span-2">
                                <label for="name" class="form-label required">{{ __('Name') }}</label>
                                <input type="text" wire:model="name" id="name" class="form-input" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea wire:model="description" id="description" rows="3" class="form-textarea"></textarea>
                                @error('description') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Type -->
                            <div class="sm:col-span-2">
                                <label for="type" class="form-label required">{{ __('Program Type') }}</label>
                                <div class="mt-1 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                        <input type="radio" wire:model="type" value="points" class="sr-only" aria-labelledby="type-points-label">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span id="type-points-label" class="block text-sm font-medium text-gray-900">{{ __('Points Based') }}</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">{{ __('Earn points for purchases') }}</span>
                                            </span>
                                        </span>
                                        <i class="fas fa-star text-yellow-400 ml-4 flex-shrink-0 self-center"></i>
                                        <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"
                                            :class="{ 'border-blue-500': type === 'points', 'border-transparent': type !== 'points' }"></span>
                                    </label>

                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                        <input type="radio" wire:model="type" value="amount" class="sr-only" aria-labelledby="type-amount-label">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span id="type-amount-label" class="block text-sm font-medium text-gray-900">{{ __('Amount Based') }}</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">{{ __('Spend to get rewards') }}</span>
                                            </span>
                                        </span>
                                        <i class="fas fa-dollar-sign text-green-500 ml-4 flex-shrink-0 self-center"></i>
                                        <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"
                                            :class="{ 'border-blue-500': type === 'amount', 'border-transparent': type !== 'amount' }"></span>
                                    </label>

                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none">
                                        <input type="radio" wire:model="type" value="visits" class="sr-only" aria-labelledby="type-visits-label">
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span id="type-visits-label" class="block text-sm font-medium text-gray-900">{{ __('Visits Based') }}</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">{{ __('Visit to get rewards') }}</span>
                                            </span>
                                        </span>
                                        <i class="fas fa-clock text-purple-500 ml-4 flex-shrink-0 self-center"></i>
                                        <span class="pointer-events-none absolute -inset-px rounded-lg border-2" aria-hidden="true"
                                            :class="{ 'border-blue-500': type === 'visits', 'border-transparent': type !== 'visits' }"></span>
                                    </label>
                                </div>
                                @error('type') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Program Rules -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Program Rules') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @if($type === 'points')
                                <!-- Points per Currency -->
                                <div>
                                    <label for="points_per_currency" class="form-label required">{{ __('Points per Dollar') }}</label>
                                    <input type="number" wire:model="points_per_currency" id="points_per_currency"
                                        class="form-input" min="0" step="1" required>
                                    @error('points_per_currency') <span class="form-error">{{ $message }}</span> @enderror
                                </div>

                                <!-- Points Required -->
                                <div>
                                    <label for="points_required" class="form-label required">{{ __('Points Required for Reward') }}</label>
                                    <input type="number" wire:model="points_required" id="points_required"
                                        class="form-input" min="0" step="1" required>
                                    @error('points_required') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                            @elseif($type === 'amount')
                                <!-- Minimum Purchase -->
                                <div>
                                    <label for="minimum_purchase" class="form-label required">{{ __('Minimum Purchase Amount') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" wire:model="minimum_purchase" id="minimum_purchase"
                                            class="form-input pl-7" step="0.01" min="0" required>
                                    </div>
                                    @error('minimum_purchase') <span class="form-error">{{ $message }}</span> @enderror
                                </div>

                                <!-- Reward Value -->
                                <div>
                                    <label for="reward_value" class="form-label required">{{ __('Reward Value') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" wire:model="reward_value" id="reward_value"
                                            class="form-input pl-7" step="0.01" min="0" required>
                                    </div>
                                    @error('reward_value') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                            @elseif($type === 'visits')
                                <!-- Visits Required -->
                                <div>
                                    <label for="visits_required" class="form-label required">{{ __('Visits Required') }}</label>
                                    <input type="number" wire:model="visits_required" id="visits_required"
                                        class="form-input" min="0" step="1" required>
                                    @error('visits_required') <span class="form-error">{{ $message }}</span> @enderror
                                </div>

                                <!-- Reward Value -->
                                <div>
                                    <label for="reward_value" class="form-label required">{{ __('Reward Value') }}</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" wire:model="reward_value" id="reward_value"
                                            class="form-input pl-7" step="0.01" min="0" required>
                                    </div>
                                    @error('reward_value') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Program Period -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Program Period') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="form-label required">{{ __('Start Date') }}</label>
                                <input type="date" wire:model="start_date" id="start_date" class="form-input" required>
                                @error('start_date') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                                <input type="date" wire:model="end_date" id="end_date" class="form-input"
                                    min="{{ $start_date }}">
                                @error('end_date') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Additional Settings') }}</h4>
                        <div class="space-y-4">
                            <!-- Conditions -->
                            <div>
                                <label for="conditions" class="form-label">{{ __('Terms & Conditions') }}</label>
                                <textarea wire:model="conditions" id="conditions" rows="3" class="form-textarea"></textarea>
                                @error('conditions') <span class="form-error">{{ $message }}</span> @enderror
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
                                    <span class="text-sm text-gray-500">{{ __('Program is available for customers') }}</span>
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