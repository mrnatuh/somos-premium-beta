<div class="flex flex-col mt-10 overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr>
                @foreach($events['labels'] as $label)
                <th class="p-3 w-[125px]">
                    <span class="flex text-sm font-normal text-[#b1b1b1] justify-center">
                        {{ $label }}
                    </span>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($events['rows'] as $rows)
            @php
                $rowIndex = $loop->index;
            @endphp
            <tr class="{{ (int) $rowIndex % 2 == 0 ? '' : 'bg-gray-100' }}">
                @foreach($rows as $row)
                @php
                    $columnIndex = $loop->index;
                @endphp
                <td>
                    <input
                        type="{{ $row['type'] ?? 'text' }}"
                        value="{{ $row['value'] }}"
                        class="flex text-center justify-center border-0 bg-transparent py-2 w-full"
                        {{ isset($row['disabled']) ? 'disabled' : '' }}
                        @if(isset($row['name']) && ($row['name'] == 'qty' || $row['name'] == 'value'))
                        wire:change.lazy="updateRow({{ $rowIndex }}, {{ $columnIndex }}, $event.target.value)"
                        @endif
                    />
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

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
