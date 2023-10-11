<div class="flex flex-col relative w-full">
    <form wire:submit.prevent="getClients">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>

            <input type="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cliente..." required wire:model="q">

            <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" wire:loading.attr="disabled">
                Buscar

                <x-status.loading />
            </button>
        </div>
    </form>

    @error('q')
    <p class="text-red-500 text-sm p-4">
        {{ $message }}
    </p>
    @enderror

    @if (sizeof($clients))
    <div class="bg-white p-3 absolute flex flex-col flex-shrink flex-grow w-full top-14 z-50 h-[320px] overflow-x-auto">
        <ul>
            @foreach($clients as $client)
            <li class="flex items-center justify-between gap-4 bg-white border rounded p-4 mt-2 shadow">
                <div>
                    <h3>{{ trim($client->A1_NOME) }}</h3>
                    <p>{{ trim($client->A1_CC) }} - {{ trim($client->A1_CGC) }}</p>
                </div>

                <button type="button" class="text-white bg-black hover:bg-black/80 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" wire:click="sendData('{{ $client->A1_COD }}')">
                    Adicionar
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
