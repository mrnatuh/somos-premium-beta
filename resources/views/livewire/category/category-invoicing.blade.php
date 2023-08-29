<div class="flex flex-col justify-between h-full">
    <div class="flex flex-col h-full justify-between mt-10 overflow-x-auto">
        <div class="flex flex-col">
            <div class="flex mb-3">
                <div class="flex text-sm font-normal text-[#b1b1b1] p-3 w-[100px]">
                    Cliente
                </div>

                @foreach($companies as $company)
                <div class="flex text-sm font-normal text-[#b1b1b1] p-3 w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] justify-center">
                    <span class="flex w-full text-[32px] text-[#404D61] justify-center">
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
                        <tr>
                            <td class="p-3">
                                01
                            </td>
                        </tr>

                        <tr class="bg-gray-100">
                            <td class="p-3">
                                02
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">
                                03
                            </td>
                        </tr>

                        <tr class="bg-gray-100">
                            <td class="p-3">
                                04
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">
                                05
                            </td>
                        </tr>

                        <tr class="bg-gray-100">
                            <td class="p-3">
                                06
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3">
                                07
                            </td>
                        </tr>

                        <tr class="bg-gray-100">
                            <td class="p-3">
                                08
                            </td>
                        </tr>
                    </tbody>
                </table>

                @foreach($companies as $company)
                @php
                    $companyIndex = $loop->index;
                @endphp
                <table class="w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] border-r">
                    <thead>
                        <tr>
                            @foreach($company['labels'] as $label)
                            <th class="p-3 w-[125px]">
                                <span class="flex text-sm font-normal text-[#b1b1b1] justify-center">
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

                <button class="flex justify-center items-center border-2 border-dashed w-[125px] rounded-xl ml-3" wire:click="toggleDrawer('clientes')">
                    <span class="text-5xl text-gray-200">
                        +
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="w-full flex items-center justify-end gap-4">
        <button
            disabled="true"
            wire:click="save"
            class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-xl font-bold disabled:opacity-50 disabled:cursor-not-allowed">
            Salvar
        </button>

        <a href="{{ route('previa') }}" class="bg-red-600 px-6 py-2 text-white rounded-xl text-xl font-bold">
            Cancelar
        </a>
    </div>

    <x-drawer
        :show="$drawers['clientes']['show']"
        drawer="clientes"
    >
        <h3 class="text-2xl">Adicionar Cliente</h3>

        <div class="mt-4">
            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar..." required>
        </div>
    </x-drawer>
</div>
