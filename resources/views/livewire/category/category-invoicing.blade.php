<div class="flex flex-col justify-between w-full">

    <div class="mt-10">
        <livewire:search-client />
    </div>

    <div class="flex mt-10 overflow-x-scroll">
        <div class="flex flex-col">
            <div class="flex mb-3">
                <div class="flex text-sm font-normal text-[#b1b1b1] py-3 w-[100px]">
                    Cliente
                </div>

                @foreach($companies as $company)
                <div class="flex text-sm font-normal text-[#b1b1b1] w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] overflow-hidden">
                    <span class="flex w-full text-[22px] text-[#404D61]  leading-normal">
                        {{ $company['title'] }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="flex">
                <table class="w-[100px] border-r">
                    <thead>
                        <tr>
                            <th class="p-3">
                                <span class="flex text-sm font-normal text-[#b1b1b1]">
                                    Dia
                                </span>
                            </th>
                        </tr>

                        <tr class="bg-gray-100">
                            <th class="p-3">
                                <span class="flex text-[16px] text-[#b1b1b1]">
                                    Preço
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($day = 1; $day <= $lastOfMonth; $day++)
                        <tr class="{{ $day % 2 == 0 ? 'bg-gray-100' : '' }}">
                            <td class="p-3">
                                {{ $day < 10  ? (int) '0' . $day : $day }}
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>

                <div class="flex w-full overflow-x-auto">
                @foreach($companies as $company)
                @php
                    $companyIndex = $loop->index;
                @endphp
                <table class="w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] border-r">
                    <thead>
                        <tr>
                            @foreach($company['labels'] as $label)
                            <th class="w-[125px] h-11">
                                <span class="w-full text-sm font-normal text-[#b1b1b1] justify-center">
                                    {{ $label }}
                                </span>
                            </th>
                            @endforeach
                        </tr>

                        <tr class="bg-gray-100">
                            @foreach($company['prices'] as $price)
                            <th class="p-3 w-[125px]">
                                <input
                                    type="text"
                                    value="{{ $price['value'] }}"
                                    class="flex text-[16px] font-normal text-[#b1b1b1]  text-center justify-center border-0 bg-transparent p-0 w-full"
                                    disabled
                                />
                            </th>
                            @endforeach
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
                                    class="flex text-center justify-center border-0 bg-transparent p-0 w-full"
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
        <div>
            <button
                disabled="true"
                wire:click="save"
                class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                Salvar
            </button>

            <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-lg text-xl font-bold">
                Cancelar
            </a>
        </div>
    </div>

    <div class="h-20"></div>
</div>
