<div class="flex flex-col mt-10">
    <livewire:search-client />

    <div class="flex flex-col flex-shrink flex-grow mt-10">
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
                    <th class="p-3 w-[125px]">&nbsp;</th>
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
                    <td class="p-3 w-[125px]">
                        @if (isset($row['type']) && ($row['type'] == 'number' || $row['type'] == 'date' || $row['type'] == 'text'))
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
                        <div class="cursor-default flex w-full p-2">
                            {{ $row['value'] }}
                        </div>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center p-3 w-[125px]">
                        @if(isset($this->deleteItem[$rowIndex]) && $this->deleteItem[$rowIndex])
                            <button
                                type="button"
                                wire:click.lazy="confirmDeleteItem({{ $rowIndex }})"
                                class="text-green-500 p-1"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg>
                            </button>

                            <button
                                type="button"
                                wire:click.lazy="cancelDeleteItem({{ $rowIndex }})"
                                class="text-red-500 p-1"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z"></path><path d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z"></path></svg>
                        @else
                        <button
                            type="button"
                            wire:click.lazy="deleteRowItem({{ $rowIndex }})"
                        >
                            <img src="/img/delete.svg" />
                        </button>
                        @endif
                    </td>
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
