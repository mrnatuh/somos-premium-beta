<div class="flex flex-col justify-between w-full">

    <div class="flex w-full flex-shrink flex-grow mt-10 gap-3 items-center">
        <select class="rounded-lg border border-gray-300 p-2" wire:model.lazy="parameter" wire:change.lazy="handleParameter">
            <option value>Selecionar parâmetros</option>
            @foreach($parameters_options as $param)
            <option value="{{ $param['value'] }}">
                {{ $param['label'] }}
            </option>
            @endforeach
        </select>

        <a
            href="{{ route('category.parameters') }}"
            class="text-gray-500 text-sm flex items-center gap-2 border border-gray-300 p-2.5 px-3 rounded-xl"
        >
            <x-icons.engine />

            Gerenciar Parâmetros
        </a>
    </div>

    @if(isset($parameters['labels']))
    <div class="flex w-full mt-10 gap-3">
        <div class="flex flex-col mb-3 gap-3">
            <label for="qtde_dias_seg_sab" class="w-full text-center text-gray-400">Dias<br /> Seg a Sáb</label>
            <input type="number" id="qtde_dias_seg_sab" placeholder="Dias Seg a Sáb" max="31" value="30" class="rounded-lg border border-gray-300 p-2 w-[100px] justify-center flex items-center text-center" />
        </div>

        <div class="flex flex-col mb-3 gap-3">
            <label for="qtde_dias_domingos_feriados" class="w-full text-center text-gray-400">Domingos<br /> Feriados</label>
            <input type="number" id="qtde_dias_domingos_feriados" placeholder="Domingos e Feriados" value="0" class="rounded-lg border border-gray-300 p-2 w-[100px] justify-center flex items-center text-center" />
        </div>
    </div>


    <div class="flex w-full flex-shrink flex-grow mt-4">
        @php
            $tdWidth = 100;
            $width = sizeof($parameters['labels']) * $tdWidth;
        @endphp
        <table class="no-wrap cursor-default" style="width: {{ $width }}px">
            <thead>
                <tr>
                    @foreach($parameters['labels'] as $row)
                        <th class="p-2 w-[{{ $tdWidth }}px]">
                            <div class="flex flex-col text-sm font-normal text-[#b1b1b1] justify-end items-center h-full">
                                {!! $row['label'] !!}
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($parameters['rows'] as $rows)
                @php
                    $rowIndex = $loop->index;
                @endphp
                <tr class="{{ (int) $rowIndex % 2 == 0 ? '' : 'bg-gray-100' }}">
                    @foreach($rows as $row)
                    @php
                        $columnIndex = $loop->index;
                    @endphp
                    <td class="text-center p-2 text-sm text-[#404D61]">
                        @if (isset($row['type']) && $row['type'] == 'number')
                        <input
                            type="{{ $row['type'] ?? 'text' }}"
                            value="{{ $row['value'] }}"
                            class="flex text-center text-sm justify-center border-0 bg-transparent py-2 w-full"
                        />
                        @else
                            {{ $row['label'] }}
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="border-dashed border-2 w-full h-[120px] flex items-center justify-center mt-10">
        <span class="text-xs uppercase text-gray-500">Selecionar parâmetros</span>
    </div>
    @endif

    <div class="flex flex-shrink flex-gro w-full mt-4 overflow-auto">
        @php
            $width = sizeof($mo['labels']) * 125;
        @endphp
        <table class="cursor-default" style="width: {{ $width }}px">
            <thead>
                <tr>
                    @foreach($mo['labels'] as $row)
                        <th class="p-2 w-[125px]">
                            <div class="flex flex-col text-sm font-normal text-[#b1b1b1] items-center h-full">
                                {!! $row['label'] !!}
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mo['rows'] as $row)
                @php
                    $rowIndex = $loop->index;
                @endphp
                <tr class="{{ (int) $rowIndex % 2 == 0 ? '' : 'bg-gray-100' }}">
                    @foreach($row as $item)
                    <td class="{{ isset($item['align']) ? $item['align'] : 'text-center' }} p-2">
                        @if (isset($item['type']) && $item['type'] == 'select')
                            <select class="flex text-center text-sm justify-center border-0 bg-transparent w-[100px]">
                                <option value="1" {{ $item['value'] === 1 ? 'selected="selected"' : '' }}>Sim</option>
                                <option value="0" {{ $item['value'] === 0 ? 'selected="selected"' : '' }}>Não</option>
                            </select>
                        @elseif(isset($item['type']) && $item['type'] == 'number')
                            <input type="number" id="qtde_dias_trabalhados" placeholder="Dias trabalhados" max="31" value="{{ $item['value'] }}" class="w-full justify-center border-0 bg-transparent flex items-center text-center" />
                        @else
                            {{ $item['label'] ?? '-' }}
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">
        <button
            disabled="true"
            wire:click="save"
            class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-xl font-bold disabled:opacity-50 disabled:cursor-not-allowed">
            Salvar
        </button>

        <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-xl text-xl font-bold">
            Cancelar
        </a>
    </div>

    <div class="h-20"></div>
</div>
