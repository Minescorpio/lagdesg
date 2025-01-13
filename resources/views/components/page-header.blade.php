@props(['title', 'backUrl' => null, 'backLabel' => null])

<div class="flex items-center justify-between">
    <h2 class="text-xl font-semibold leading-tight text-white">
        {{ $title }}
    </h2>
    @if($backUrl)
        <a href="{{ $backUrl }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700">
            {{ $backLabel }}
        </a>
    @endif
</div> 