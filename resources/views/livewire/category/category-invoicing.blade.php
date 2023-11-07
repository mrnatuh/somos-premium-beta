<div class="flex flex-col justify-between w-full">
    <div class="mt-10">
        <livewire:search-client />
    </div>

    <div class="flex mt-10 overflow-x-scroll">
        <div class="flex flex-col">
            <div class="flex flex-shrink flex-grow">
                <div class="border-r block text-sm font-normal text-[#b1b1b1] p-3 w-[100px]">
                    Cliente
                </div>

                @foreach($companies as $company)
                @php
                    $id = $company['id'];
                @endphp
                <div class="border-r flex flex-shrink flex-grow text-sm font-normal text-[#b1b1b1] w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] overflow-hidden relative p-4 m-0">
                    <span class="flex w-full  justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                        {{ $company['title'] }}
                    </span>

                    <div class="absolute top-0 left-0">
                        <div class="flex gap-1">
                            @if(isset($deleteItem[$id]) && $deleteItem[$id])
                                <button
                                    type="button"
                                    wire:click.lazy="confirmDeleteItem('{{ $id }}')"
                                    class="text-green-500 p-1 bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all py-1 px-2.5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg>
                                </button>

                                <button
                                    type="button"
                                    wire:click.lazy="cancelDeleteItem('{{ $id }}')"
                                    class="text-red-500 p-1 bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all py-1 px-2.5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z"></path><path d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z"></path></svg>
                            @else
                            <button
                                type="button"
                                wire:click.lazy="deleteRowItem('{{ $company['id'] }}')"
                                class="bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all p-1"
                            >
                                <img src="/img/delete.svg" />
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex flex-nowrap">
                <div class="flex flex-shrink flex-grow">
                    <table class="table w-[100px] border-r">
                    <thead>
                        <tr>
                            <th class="p-3 w-[100px]">
                                <span class="flex text-sm font-normal text-[#b1b1b1]">
                                    Dia
                                </span>
                            </th>
                        </tr>

                        <tr class="bg-gray-100">
                            <th class="p-3 w-[100px]">
                                <span class="flex text-[16px] text-[#b1b1b1]">
                                    Preço
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($day = 1; $day <= $lastOfMonth; $day++)
                        <tr class="{{ $day % 2 == 0 ? 'bg-gray-100' : '' }}">
                            <td class="p-3 w-[100px]">
                                {{ $day < 10  ? (int) '0' . $day : $day }}
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                    </table>
                </div>

                <div class="flex flex-grow flex-shrink w-full overflow-x-auto">
                @foreach($companies as $company)
                @php
                    $companyIndex = $loop->index;
                @endphp
                <table class="w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] border-r">
                    <thead>
                        <tr>
                            @if(sizeof($company['labels']))
                                @foreach($company['labels'] as $label)
                                @php
                                    $labelIndex = $loop->index;
                                @endphp
                                <th class="w-[125px] h-11 relative">
                                    <span class="w-full text-sm font-normal text-[#b1b1b1] justify-center uppercase">
                                        {{ $label }}
                                    </span>

                                    <div class="absolute top-1/2 left-0 -translate-y-1/2">
                                        <div class="flex gap-1">
                                            @if(isset($deleteCompanyColumn[$companyIndex][$labelIndex]) && $deleteCompanyColumn[$companyIndex][$labelIndex])
                                                <button
                                                    type="button"
                                                    wire:click.lazy="confirmDeleteColumnItem('{{ $companyIndex }}', '{{ $labelIndex }}')"
                                                    class="text-green-500 p-1 bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all py-1 px-2.5"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg>
                                                </button>

                                                <button
                                                    type="button"
                                                    wire:click.lazy="cancelDeleteColumnItem('{{ $companyIndex }}', '{{ $labelIndex }}')"
                                                    class="text-red-500 p-1 bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all py-1 px-2.5"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z"></path><path d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z"></path></svg>
                                            @else
                                                <button
                                                    type="button"
                                                    wire:click.lazy="deleteColumnItem('{{ $companyIndex }}', '{{ $labelIndex }}')"
                                                    class="bg-white opacity-75 hover:opacity-100 hover:z-40 transition-all p-1"
                                                >
                                                    <img src="/img/delete.svg" />
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                @endforeach
                            @endif

                            <td class="w-[125px] h-11">
                                <select
                                    class="flex flex-shrink flex-grow w-full text-sm font-normal text-[#b1b1b1] justify-center"
                                    wire:change.lazy="addColumnPrice({{ $companyIndex }}, $event.target.value)"
                                >
                                    <option value="" selected="true">Selecione</option>
                                    @foreach($company['prices_options'] as $option)
                                        @if(!in_array($option['value'], $company['prices_selected']))
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr class="bg-gray-100">
                            @if(sizeof($company['prices']))
                                @foreach($company['prices'] as $price)
                                <th class="p-3 w-[125px]">
                                    <input
                                        type="text"
                                        value="{{ number_format($price['value'], 2, ',', '.') }}"
                                        class="flex text-[16px] font-normal text-[#b1b1b1]  text-center justify-center border-0 bg-transparent p-0 w-full"
                                        disabled
                                    />
                                </th>
                                @endforeach
                            @endif

                            <th class="p-3 w-[125px]">
                                <span class="flex text-[16px] font-normal text-[#b1b1b1]  text-center justify-center border-0 bg-transparent p-0 w-full">
                                0,00
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($company['rows'] as $rows)
                        @php
                            $rowIndex = $loop->index;
                        @endphp
                        <tr class="{{ (int) $loop->index % 2 == 0 ? '' : 'bg-gray-100' }}">
                            @foreach($rows as $row)
                            @php
                                $qtyIndex = $loop->index;
                            @endphp
                            <td class="p-3 w-[125px]">
                                <input
                                    type="text"
                                    value="{{ $row['value'] }}"
                                    wire:change.lazy="updateQty({{ $companyIndex }}, {{ $rowIndex }}, {{ $qtyIndex }}, $event.target.value)"
                                    class="flex text-center justify-center border-0 bg-transparent p-0 w-full disabled:text-gray-400"
                                    {{ $row['name'] == 'OUTRO' ? 'disabled="true"' : '' }}"
                                />
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">

        <button
            wire:click="save"
            class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative">
            Salvar

            <x-status.loading />
        </button>

        <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-lg text-xl font-bold relative">
            Cancelar

            <x-status.loading />
        </a>

    </div>

    <div class="h-20"></div>
</div>
