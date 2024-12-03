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

            <div class="flex">
                <livewire:notifications />
            </div>
        </div>

        <div class="flex flex-col w-full overflow-x-auto">
          <livewire:dashboard.dashboard-bar />
        </div>

        <div class="flex justify-between gap-4 mt-10">
            <div class="flex">
				      @if (!$realizadas)
                <a
                    href="{{ route('preview.create') }}"
                    class="flex gap-1 items-center justify-center h-12 px-2.5 mr-2 mb-2 text-[16px] font-medium text-blue-600 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                      </svg>

                      Nova prévia
                </a>
				      @endif

                {{-- <button class="py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Visualizar
                </button> --}}
            </div>

            {{-- <livewire:months-scroll /> --}}
            <div class="flex">
              @php
                $month_options = [
                  '01' => 'Janeiro',
                  '02' => 'Fevereiro',
                  '03' => 'Março',
                  '04' => 'Abril',
                  '05' => 'Maio',
                  '06' => 'Junho',
                  '07' => 'Julho',
                  '08' => 'Agosto',
                  '09' => 'Setembro',
                  '10' => 'Outubro',
                  '11' => 'Novembro',
                  '12' => 'Dezembro'
                ];

                $year_options = [
                  '2023' => '2023',
                  '2024' => '2024',
                  '2025' => '2025',
                  '2026' => '2026',
                  '2027' => '2027',
                  '2028' => '2028',
                  '2029' => '2029',
                ];
              @endphp

              <form method="GET" action="/previa" class="flex gap-2 items-center justify-center">
                <input type="text" name="q" placeholder="Procurar prévia..." class="flex py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 placeholder:text-gray-400" maxlength="100" minlength="3" value="{{ $q }}" />

                <select class="flex py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200" name="m">
                  <option value=''>- Mês -</option>
                  @foreach ($month_options as $key => $value)
                    <option value="{{ $key }}" @if (isset($month) && $month == $key)selected @endif>{{ $value }}</option>
                  @endforeach
                </select>

                <select class="flex py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200" name="y">
                  <option value=''>- Ano -</option>
                  @foreach ($year_options as $key => $value)
                    <option value="{{ $key }}" @if (isset($year) && $year == $key)selected @endif>{{ $value }}</option>
                  @endforeach
                </select>

                <button type="submit" class="flex items-start justify-center w-8 h-8 text-[16px] font-medium text-blue-500 bg-slate-100 rounded">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-full relative -top-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                  </svg>
                </button>
              </form>
            </div>
        </div>

        <span class="block h-10"></span>

        <x-table.preview-table 
          :previews="$previews" 
          :realizadas="$realizadas ?? 0" 
        />

        <span class="block h-10"></span>

        {{ $previews->withQueryString()->links() }}
    </x-dashboard.container>
</div>
