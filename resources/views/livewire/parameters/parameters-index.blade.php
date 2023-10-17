<div class="flex flex-col w-full h-full p-8 relative">

    @if (session('success'))
        <div class="alert bg-green-500 text-white fixed top-0 left-1/2 -translate-x-1/2 rounded p-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert bg-red-500 text-white fixed top-0 left-1/2 -translate-x-1/2  rounded p-4">
            {{ session('error') }}
        </div>
    @endif

    <livewire:category.category-header title="Parâmetros para MO" />

    <hr />

    <div class="flex flex-col  w-full mt-10 mb-14">
        <div class="flex  flex-col gap-1 mb-10">
            <input type="text" wire:model="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nome dos parâmetros, ex: Parâmetros 1" />

            @error('name')
                <span class="text-red-500 px-2 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-10">
            @php
                $tdWidth = 100;
                $width = sizeof($parameters['labels']) * $tdWidth;
            @endphp
            <table class="no-wrap cursor-default" style="width: {{ $width }}px">
                <thead>
                    <tr>
                        @foreach($parameters['labels'] as $row)
                            <th class="p-2 w-[{{ $tdWidth }}px] border-r border-b">
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
                        <td class="text-sm text-[#404D61] border-r">
                            <div class="flex items-center justify-center">
                            @if (isset($row['type']) && $row['type'] == 'number')
                            <input
                                type="{{ $row['type'] ?? 'text' }}"
                                value="{{ $row['value'] }}"
                                class="flex text-center text-sm justify-center border-0 bg-transparent py-2 w-full items-center"
                                wire:change.lazy="updateRow({{ $rowIndex }}, {{ $columnIndex }}, $event.target.value)"
                            />
                            @else
                                {{ $row['label'] }}
                            @endif
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-center">
            <x-primary-button class="relative" wire:click.lazy="save">
                Criar

                <x-status.loading />
            </x-primary-button>
        </div>
    </div>

    <hr />

        <div class="flex w-full mt-10">
            <table class="w-full mb-10">
                <thead>
                    <tr>

                        <th class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal text-[#b1b1b1]">
                                Id
                            </span>
                        </th>

                        <th class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal text-[#b1b1b1]">
                                Nome
                            </span>
                        </th>

                         <th class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal text-[#b1b1b1] justify-center">
                                Criado em
                            </span>
                        </th>

                        <th class="p-3 w-[125px]">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)

                    <tr class="border-b {{ (int) $rowIndex % 2 == 0 ? '' : 'bg-gray-100' }}">
                        <td class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal">
                                {{ $row['id'] }}
                            </span>
                        </td>

                        <td class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal">
                                {{ $row['name'] }}
                            </span>
                        </td>

                        <td class="p-3 w-[125px]">
                            <span class="flex text-sm font-normal justify-center">
                                {{ $row->created_at->format('d-m-Y H:i') }}
                            </span>
                        </td>

                        <td class="text-center p-3 w-[125px]">
                            {{-- @if(isset($this->deleteItem[$rowIndex]) && $this->deleteItem[$rowIndex])
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
                            @endif --}}
                            <button
                                type="button"
                                wire:click.lazy="deleteRowItem({{ $rowIndex }})"
                            >
                                <img src="/img/delete.svg" />
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    <hr />

    <div class="w-full flex mt-14">
        <a href="{{ route('category', [ 'filter' => 'mo' ]) }}" class="bg-blue-500 text-white p-4 rounded-xl">
            Voltar para MO
        </a>
    </div>

</div>
