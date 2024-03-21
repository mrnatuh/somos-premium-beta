<div class="w-full">
    <x-dashboard.container>
        <div class="flex border-b items-center justify-between pb-10 mb-10">
            <div>
                <strong class="text-2xl">{{ $realizadas ? "Realizadas" : "Prévias" }}</strong>
                <p class="text-gray-600">
                    {{ sizeof($previews) }} 
                    {{ sizeof($previews) > 1 || sizeof($previews) == 0 ? 'resultados encontrados' : 'resultado encontrado' }}
                </p>
            </div>

            {{-- <div class="flex gap-4">
                <div class="flex w-full relative max-w-[470px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.5291 19C15.9473 19 19.5291 15.4183 19.5291 11C19.5291 6.58172 15.9473 3 11.5291 3C7.11077 3 3.52905 6.58172 3.52905 11C3.52905 15.4183 7.11077 19 11.5291 19Z" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21.5291 21L17.1791 16.65" stroke="#757D8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <input type="search" id="preview-search" class="flex w-[470px] p-4 pl-14 text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-4 focus:ring-blue-100 dark:bg-gray-700 dark:border-gray-600 placeholder-gray-300 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Prévia" required>
                </div>

                <button type="submit" class="text-white bg-[#5B6AD0] hover:opacity-90 focus:ring-4 focus:outline-none focus:ring-blue-100 font-medium rounded-[10px] text-[16px] px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all">Buscar</button>
            </div> --}}

            <div class="flex">
                <livewire:notifications />
            </div>
        </div>

        <livewire:dashboard.dashboard-bar />

        <div class="flex justify-between gap-4 mt-10">
            <div class="flex">
								@if (!$realizadas)
                <a
                    wire:navigate
                    href="{{ route('preview.create') }}"
                    class="py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer">
                        Adicionar
                </a>
								@endif

                {{-- <button class="py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Visualizar
                </button> --}}
            </div>

            <livewire:months-scroll />
        </div>

        <x-table.preview-table :previews="$previews" :realizadas="$realizadas ?? 0" />
    </x-dashboard.container>
</div>
