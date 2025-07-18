@props([
    'previews' => [],
    'realizadas' => 0,
])

@php
    $preview_status = [
        'em-analise' => 'Em análise',
        'validado' => 'Validado',
        'recusado' => 'Recusado'
    ];
@endphp

<div class="w-full flex max-w-full overflow-x-auto">
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
                  	  Faturamento

									</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    Eventos</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
											MP
										</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    MO
									</th>
                <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    GD
									</th>
                    <th
                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                    ROU
										</th>
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
                <td class="px-4 align-middle font-medium h-10">
                  <div class="flex items-center justify-center h-full">
                    <form action="{{ route('preview.edit') }}" method="post">
                        @csrf
                        @method('post')
                        <input type="hidden" name="weekref" value="{{ $preview->week_ref }}" />
                        <input type="hidden" name="cc" value="{{ $preview->cc }}" />

                        <input type="hidden" name="realizadas" value="{{ $realizadas ? 1 : 0 }}" />

                        <button type="submit" class="hover:underline">
                            {{ $preview->week_ref }}
                        </button>
                    </form>
                  </div>
                </td>
                @if(!Auth::user()->cc)
                <td class="px-4 align-middle">{{ $preview->cc }}</td>
                @endif
                <td class="px-4 align-middle">
									<div class="flex items-center gap-1 min-w-[80px] h-[62px]">
                    <span class="inline-block">R$ {{ $preview->invoicing ? number_format($preview->invoicing, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-table='{"group":"0001","cc":"{{ $preview->cc }}","month_ref":"{{ $preview->month_ref}}","total":{{ $preview->invoicing }}}'></span>
									</div>
                </td>
                <td class="px-4 align-middle">
                    R$ {{ $preview->events ? number_format($preview->events, 2, ',', '.') : '0,00' }}</td>
                <td class="px-4 align-middle">
									<div class="flex items-center min-w-[80px] gap-1">
                    <span class="inline-block">R$ {{ $preview->mp ? number_format($preview->mp, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-table='{"group":"0003","cc":"{{ $preview->cc }}","month_ref": "{{ $preview->month_ref}}","total":{{ $preview->mp }}}'></span>
									</div>
                </td>
                <td class="px-4 align-middle">
									<div class="flex items-center min-w-[80px]  gap-1">
                    <span class="inline-block">R$ {{ $preview->mo ? number_format($preview->mo, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-table='{"group":"0004","cc":"{{ $preview->cc }}","month_ref":"{{ $preview->month_ref}}","total":{{ $preview->mo }}}'></span>
									</div>
                </td>
                <td class="px-4 align-middle">
									<div class="flex items-center min-w-[80px]  gap-1">
                    <span class="inline-block">R$ {{ $preview->gd ? number_format($preview->gd, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-table='{"group":"0005","cc":"{{ $preview->cc }}","month_ref": "{{ $preview->month_ref}}","total":{{ $preview->gd }}}'></span>
									</div>
                </td>
                <td class="px-4 align-middle">
									<div class="flex items-center min-w-[80px]  gap-1">
                    <span class="inline-block">R$ {{ $preview->rou ? number_format($preview->rou, 2, ',', '.') : '0,00' }}</span>

                    <span class="inline-block relative" data-table='{"group":"0006", "cc": "{{ $preview->cc }}","month_ref":"{{ $preview->month_ref}}", "total":{{ $preview->rou }}}'></span>
									</div>
                </td>
                <td class="px-4 align-middle text-center ">
                    <span class="{{ $preview->status }}">{{ $preview_status[$preview->status] ?? '-' }}</span>
                </td>
                <td class="px-4 text-center align-middle">{{ $preview->variation ?? '0%' }}</td>
                <td class="px-4 align-middle">
                    <div class="inline-flex gap-4">
                      <div>
                        <form action="{{ route('preview.edit') }}" method="post">
                            @csrf
                            @method('post')
                            <input type="hidden" name="weekref" value="{{ $preview->week_ref }}" />
                            <input type="hidden" name="cc" value="{{ $preview->cc }}" />

                            <button type="submit" class="flex w-5 h-5">
                                <img src="/img/edit.svg" alt="Edit" />
                            </button>
                        </form>
                      </div>

                      {{-- <button>
                          <img src="/img/delete.svg" alt="Delete" />
                      </button> --}}

                      <a 
                        href="{{ route('preview.export', ['id' => $preview->id])}}"
                        class="flex w-6 h-6"
                        target="_blank"
                      >
                        <img src="/img/export.svg" alt="Exportar dados da prévia" />
                      </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
