<div class="flex flex-col justify-between w-full h-full">
    <div class="block w-full overflow-hidden">
        <div class="block w-full overflow-x-auto mt-10">
            @php
                $tdWidth = 100;
                $width = sizeof($parameters['labels']) * $tdWidth;
            @endphp
            <table class="sticky no-wrap cursor-default" style="width: {{ $width }}px">
                <thead>
                    <tr>
                        @foreach($parameters['labels'] as $row)
                            <th class="p-3 w-[{{ $tdWidth }}px]">
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
                        <td class="text-center p-3 text-sm text-[#404D61]">
                            {{ $row['label'] }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="block w-[88%] mt-4 overflow-x-auto">
            @php
                $width = sizeof($mo['labels']) * 125;
            @endphp
            <table class="cursor-default mt-10" style="width: {{ $width }}px">
                <thead>
                    <tr>
                        @foreach($mo['labels'] as $row)
                            <th class="p-3 w-[125px]">
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
                        <td class="text-center p-3">
                            {{ $item['label'] ?? '-' }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="w-[88%] flex items-center justify-end gap-4">
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
