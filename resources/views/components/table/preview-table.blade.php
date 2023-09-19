@props([
    'previews' => []
])

@php
    $preview_status = [
        'em-analise' => 'Em análise',
        'validado' => 'Validado',
        'recusado' => 'Recusado'
    ];
@endphp

<div class="w-full" x-data="{ preview: null }">
    <table class="w-full caption-bottom text-sm">
        <thead class="[&amp;_tr]:border-b">
            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                    Previa</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Faturamento</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    MP</th>
                <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                    MO</th>
                <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                    GD</th>
                    <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                    ROU</th>
                    <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Status</th>
                    <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                    Variação</th>
                    <th
                    class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                    &nbsp;</th>
            </tr>
        </thead>
        <tbody class="[&amp;_tr:last-child]:border-0">
            @foreach($previews as $preview)
            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">{{ $preview->week_ref }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">{{ $preview->invoicing ?? 'R$ 0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">{{ $preview->mp ?? 'R$ 0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">{{ $preview->mo ?? 'R$ 0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">{{ $preview->gd ?? 'R$ 0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">{{ $preview->rou ?? '0%' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center {{ $preview->status }}">{{ $preview_status[$preview->status] ?? '-' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">{{ $preview->variation ?? '0%' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">
                    <div class="flex gap-3">
                        <a href="{{ route('category', ['client_id' => $preview->client_id, 'week_ref' => $preview->week_ref ]) }}" wire:navigate>
                            <img src="/img/edit.svg" alt="Edit" />
                        </a>

                        <button data-modal-target="popup-delete-modal" data-modal-toggle="popup-delete-modal" @click="preview = {{ $preview->id }}">
                            <img src="/img/delete.svg" alt="Delete" />
                        </button>

                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="popup-delete-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-delete-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Tem certeza que deseja deletar?</h3>

                <div class="flex items-center justify-center">
                    <form action="{{ route('preview.delete') }}" method="post">
                        @csrf
                        @method('delete')

                        <input type="hidden" name="id" x-model="preview" />

                        <button class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2" type="submit">
                            Sim
                        </button>
                    </form>

                    <button data-modal-hide="popup-delete-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Não</button>
                </div>
            </div>
        </div>
    </div>
</div>
