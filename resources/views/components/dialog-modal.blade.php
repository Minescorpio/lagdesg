@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-white">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-300">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-[#1B1D29] text-right">
        {{ $footer }}
    </div>
</x-modal>
