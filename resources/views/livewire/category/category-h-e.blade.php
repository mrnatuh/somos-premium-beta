<div>
    <div class="flex mt-10">
        <div class="flex flex-col">
            <div class="flex flex-nowrap">
                <div class="flex flex-shrink-0 flex-grow-0">
                    <table class="table w-[100px] border-r">
                        <thead>
                            <tr>
                                <th class="h-[158px]">
                                    <div class=" flex flex-shrink-0 flex-grow-0 text-sm font-normal text-[#b1b1b1] p-3 w-[100px]">
                                        Dia
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($day = 1; $day <= $lastOfMonth; $day++)
                            <tr class="{{ $day % 2 == 0 ? 'bg-gray-100' : '' }}">
                                <td class="border-b w-[100px] leading-normal">
                                    <span class="px-3 text-sm">
                                        {{ $day < 10  ? (int) '0' . $day : $day }}
                                    </span>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <div class="flex overflow-x-auto w-[60vw]">
                @foreach($employees as $employe)
                @php
                    $employeIndex = $loop->index;
                @endphp

                <div class="w-[{{ ((int) $employe['colspan'] * 125) . 'px' }}] flex flex-grow-0 flex-shrink-0">
                    <table class="w-full border-r">
                        <thead>
                            <tr>
                                <th colspan="{{ $employe['colspan'] }}">
                                    <span class="flex w-full  justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['title'] }}
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="{{ $employe['colspan'] }}" class="text-center p-3">
                                    <span class="flex w-full  justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['salary_label'] }}
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                @foreach($employe['columns'] as $column)
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden text-sm p-1">
                                        {{ $column['label'] }}
                                    </span>
                                </td>
                                @endforeach
                            </tr>

                            <tr>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                       {{ $employe['total_hr_50'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_hr_100'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_hr_atrasos'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_hr_faltas'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_hr_adicional_noturno'] }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_vlr_50'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_vlr_100'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_vlr_atrasos'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61]  leading-normal text-center overflow-hidden">
                                        {{ $employe['total_vlr_faltas'] }}
                                    </span>
                                </td>
                                <td class="text-center w-[125px]">
                                    <span class="flex w-full justify-center text-[16px] text-[#404D61] leading-normal text-center overflow-hidden">
                                        {{ $employe['total_vlr_adicional_noturno'] }}
                                    </span>
                                </td>
                            </tr>
                        </thead>
                        <tbody class="border-r">
                            @foreach($employe['rows'] as $rows)
                            @php
                                $rowIndex = $loop->index;
                            @endphp
                            <tr class="{{ (int) $loop->index % 2 == 0 ? '' : 'bg-gray-100' }}">
                                @foreach($rows as $row)
                                @php
                                    $qtyIndex = $loop->index;
                                @endphp
                                <td class="border-b w-[125px] leading-normal">
                                    <input
                                        type="text"
                                        value="{{ $row['value'] }}"
                                        wire:change.lazy="updateQty({{ $employeIndex }}, {{ $rowIndex }}, {{ $qtyIndex }}, $event.target.value)"
                                        class="flex text-center justify-center border-0 bg-transparent p-3 w-full disabled:text-gray-400 text-sm placeholder:text-gray-300 leading-normal"
                                        x-mask="99:99:99"
                                        placeholder="99:99:99"
                                    />
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white/50 w-full flex items-center justify-end gap-4 position fixed px-10 py-5 bottom-0 right-0">

        <button
            class="bg-green-600 cursor-pointer px-6 py-2 text-white rounded-xl text-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed relative">
            Enviar para aprovação

            <x-status.loading />
        </button>
    </div>

    <div class="h-20"></div>
</div>
