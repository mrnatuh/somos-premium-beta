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

<div class="w-full">
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
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ {{ $preview->invoicing ? number_format($preview->invoicing, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ {{ $preview->events ? number_format($preview->events, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ {{ $preview->mp ? number_format($preview->mp, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ {{ $preview->mo ? number_format($preview->mo, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ {{ $preview->gd ? number_format($preview->gd, 2, ',', '.') : '0,00' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">{{ $preview->rou ?? '0%' }}</td>
                <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center ">
                    @if (Auth::user()->access == 'user')
                        <span class="{{ $preview->status }}">{{ $preview_status[$preview->status] ?? '-' }}</span>
                    @else
                    <select class="rounded border border-gray-200">
                        @foreach($preview_status as $key => $value)
                            <option value="{{ $key }}" {{ $preview->status == $key ? 'selected="selected"' : '' }} class="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @endif
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
