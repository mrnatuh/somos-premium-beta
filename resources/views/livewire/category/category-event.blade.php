<div class="flex flex-col mt-10">
    <livewire:search-client />

    <div class="flex flex-col mt-10">
        @if (sizeof($events['rows']))
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
                        @if(isset($row['type']) && $row['type'] == 'select')
                            <select class="flex text-center justify-center border-0 bg-transparent w-full">
                                <option selected value=''></option>
                                <option value="Mercado Livre" {{ $row['value'] == 'Mercado Livre' ? 'selected="selected"' : '' }}>Mercado Livre</option>
                                <option value="Graber" {{ $row['value'] == 'Graber' ? 'selected="selected"' : '' }}>Graber</option>
                            </select>
                        @elseif (isset($row['type']) && ($row['type'] == 'number' || $row['type'] == 'date' || $row['type'] == 'text'))
                        <input
                            type="{{ $row['type'] ?? 'text' }}"
                            value="{{ $row['value'] }}"
                            class="flex text-center justify-center border-0 bg-transparent py-2 w-full"
                            {{ isset($row['disabled']) ? 'disabled' : '' }}
                            @if(isset($row['name']) && ($row['name'] == 'qty' || $row['name'] == 'value'))
                            wire:change.lazy="updateRow({{ $rowIndex }}, {{ $columnIndex }}, $event.target.value)"
                            @endif
                        />
                        @else
                        <div class="cursor-default flex justify-center w-full p-2">
                            {{ $row['value'] }}
                        </div>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">
        <div>
            <button
                wire:click="save"
                class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative">
                Salvar

                <x-status.loading />
            </button>

            <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-lg text-xl font-bold">
                Cancelar
            </a>
        </div>
    </div>
</div>
