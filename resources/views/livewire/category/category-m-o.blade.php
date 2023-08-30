<div class="flex flex-col justify-between w-full h-full">
    <div class="flex flex-col max-w-[1200px] overflow-hidden">
        <div class="block w-full overflow-x-auto mt-10">
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

        <div class="block w-full mt-4 overflow-x-auto">
            @php
                $width = sizeof($mo['labels']) * 125;
            @endphp
            <table class="cursor-default mt-10" style="width: {{ $width }}px">
                <thead>
                    <tr>
                        @foreach($mo['labels'] as $row)
                            <th class="p-2 w-[125px]">
                                <div class="flex flex-col text-sm font-normal text-[#b1b1b1] justify-end items-center h-full">
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
                        <td class="text-center p-2">
                            @if (isset($item['type']) && $item['type'] == 'select')
                                <select class="flex text-center text-sm justify-center border-0 bg-transparent w-full">
                                    <option value="1" {{ $item['value'] === 1 ? 'selected="selected"' : '' }}>Sim</option>
                                    <option value="0" {{ $item['value'] === 0 ? 'selected="selected"' : '' }}>Não</option>
                                </select>
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
</div>
