@props([
    'id' => '',
])

<div
    id="{{ $id }}"
    class="absolute top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-2/3 max-w-lg dark:bg-gray-800 shadow-xl"
    tabindex="-1"
    aria-labelledby="{{ $id }}-label"
>
    <div class="flex justify-end w-full">
        <button type="button" data-drawer-hide="{{ $id }}" aria-controls="select-client" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
   </div>

   {{ $slot }}
</div>
