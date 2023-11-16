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

<div class="w-full flex flex-shrink-0 flex-grow-0 overflow-x-auto">
    <table class="w-full caption-bottom text-sm">
        <thead class="[&amp;_tr]:border-b">
            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                    Previa</th>
                @if(!Auth::user()->cc)
                <td class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">Centro de Custo</td>
                @endif
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Faturamento</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Eventos</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    MP</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    MO</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    GD</th>
                    <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    ROU</th>
                    <th
                    class="h-12 px-4 text-center align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Status</th>
                    <th
                    class="h-12 px-4 text-center align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Variação</th>
                    <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody class="[&amp;_tr:last-child]:border-0">
            @foreach($previews as $preview)
            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">
                    <form action="{{ route('preview.edit') }}" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="weekref" value="{{ $preview->week_ref }}" />
                        <input type="hidden" name="cc" value="{{ $preview->cc }}" />

                        <button type="submit" class="hover:underline">
                            {{ $preview->week_ref }}
                        </button>
                    </form>
                </td>
                @if(!Auth::user()->cc)
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">{{ $preview->cc }}</td>
                @endif
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <span class="inline-block">R$ {{ $preview->invoicing ? number_format($preview->invoicing, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-group="0001" data-cc="{{ $preview->cc }}" data-month_ref="{{ $preview->month_ref}}"
                        data-value="{{ $preview->invoicing }}"></span>

                </td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    R$ {{ $preview->events ? number_format($preview->events, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <span class="inline-block">R$ {{ $preview->mp ? number_format($preview->mp, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-group="0003" data-cc="{{ $preview->cc }}"
                        data-month_ref="{{ $preview->month_ref}}" data-value="{{ $preview->mp }}"></span>
                </td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <span class="inline-block">R$ {{ $preview->mo ? number_format($preview->mo, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-group="0004" data-cc="{{ $preview->cc }}"
                        data-month_ref="{{ $preview->month_ref}}" data-value="{{ $preview->mo }}"></span>
                </td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <span class="inline-block">R$ {{ $preview->gd ? number_format($preview->gd, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-group="0005" data-cc="{{ $preview->cc }}"
                        data-month_ref="{{ $preview->month_ref}}" data-value="{{ $preview->gd }}"></span>
                </td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <span class="inline-block">R$ {{ $preview->rou ? number_format($preview->rou, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block top-1 relative" data-group="0005" data-cc="{{ $preview->cc }}"
                        data-month_ref="{{ $preview->month_ref}}" data-value="{{ $preview->rou }}"></span>
                </td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center ">
                    <span class="{{ $preview->status }}">{{ $preview_status[$preview->status] ?? '-' }}</span>
                </td>
                <td class="p-4 text-center align-middle [&amp;:has([role=checkbox])]:pr-0">{{ $preview->variation ?? '0%' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">
                    <div class="flex gap-3">
                        <form action="{{ route('preview.edit') }}" method="post">
                            @csrf
                            @method('post')
                            <input type="hidden" name="weekref" value="{{ $preview->week_ref }}" />
                            <input type="hidden" name="cc" value="{{ $preview->cc }}" />

                            <button type="submit">
                                <img src="/img/edit.svg" alt="Edit" />
                            </button>
                        </form>

                        {{-- <button>
                            <img src="/img/delete.svg" alt="Delete" />
                        </button> --}}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
