<div class="flex flex-col  h-full justify-between mt-10">
    <div class="flex flex-col">
        <table class="w-full mb-10">
            <thead>
                <tr>
                    @foreach($mp['labels'] as $label)
                    <th class="p-3 w-[125px]">
                        <span class="flex text-sm font-normal text-[#b1b1b1] justify-center">
                            {{ $label }}
                        </span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($mp['rows'] as $rows)
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
                            wire:change.lazy="updateRow({{ $rowIndex }}, {{ $columnIndex }}, $event.target.value)"
                        />
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div>
            <button class="py-2.5 px-5 mr-2 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 relative" wire:click="increment">
                Adicionar item

                <x-status.loading />
            </button>
        </div>
    </div>

    <div class="w-full flex items-center justify-end gap-4">
        <button
            wire:click="save"
            class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-xl font-bold disabled:opacity-50 relative">
            Salvar

            <x-status.loading />
        </button>

        <a href="{{ route('preview') }}" class="bg-red-600 px-6 py-2 text-white rounded-xl text-xl font-bold">
            Cancelar
        </a>
    </div>
</div>
