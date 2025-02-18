<div>
    @if (isset($audioPath))
        <a href="{{ asset('/storage/' . $audioPath) }}" target="_blank" class="flex items-center space-x-2">
            <span class="text-gray-400 dark:text-gray-500">Ouvir áudio</span>
            <svg class="fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6 text-gray-400 dark:text-gray-500"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 5v14l11-7-11-7z">
                </path>
            </svg>
        </a>
    @endif
</div>
