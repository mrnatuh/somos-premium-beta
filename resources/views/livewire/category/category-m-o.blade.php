<div class="flex flex-col  h-full justify-between mt-10 overflow-x-auto">
    <div class="flex flex-col">
        <table class="w-full cursor-default">
            <thead>
                <tr>
                    @foreach($parameters['labels'] as $row)
                        <th class="p-3 w-[100px]">
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
                    <td class="text-center text-sm text-[#404D61]">
                        {{ $row['label'] }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="w-full cursor-default">
            <thead>
                <tr>
                    @foreach($mo['labels'] as $row)
                        <th class="p-3 w-[100px]">
                            <div class="flex flex-col text-sm font-normal text-[#b1b1b1] justify-end items-center h-full">
                                {!! $row['label'] !!}
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </div>

    <div class="w-full flex items-center justify-end gap-4 p-6">
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
