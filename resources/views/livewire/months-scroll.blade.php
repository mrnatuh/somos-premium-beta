<div class="flex items-center gap-4 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 ">
    <button class="flex items-center py-2.5 px-5 hover:text-blue-700 hover:bg-gray-100 transition-colors h-full" wire:click="decrement">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div>
        {{ $month['label'] }}, {{ $year }}
    </div>

    <button class="flex items-center py-2.5 px-5 hover:text-blue-700 hover:bg-gray-100 transition-colors h-full" wire:click="increment">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 18L15 12L9 6" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>
