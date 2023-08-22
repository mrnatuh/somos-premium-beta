<div class="flex flex-col mt-10 overflow-x-auto">

    <div class="flex mb-3">
        <div class="flex text-sm font-normal text-[#b1b1b1] p-3 w-[100px]">
            Cliente
        </div>

        @foreach($companies as $company)
        <div class="flex text-sm font-normal text-[#b1b1b1] p-3 w-[{{ ((int) $company['colspan'] * 125) . 'px' }}] justify-center">
            <span class="text-[32px] text-[#404D61]">
                {{ $company['title'] }}
            </span>
        </div>
        @endforeach

        {{-- <button class="flex justify-center items-center border-2 border-dashed w-[125px] rounded-xl ml-3">
            <span class="text-xl text-gray-200">
                +
            </span>
        </button> --}}
    </div>

    <div class="flex">
        <table class="w-[100px]">
            <thead>
                <tr>
                    <th class="p-3">
                        <span class="flex text-sm font-normal text-[#b1b1b1]">
                            Dia
                        </span>
                    </th>
                </tr>

                <tr class="bg-gray-100">
                    <th class="p-3">
                        <span class="flex text-[16px] text-[#b1b1b1]">
                            Preço
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3">
                        01
                    </td>
                </tr>

                <tr class="bg-gray-100">
                    <td class="p-3">
                        02
                    </td>
                </tr>

                <tr>
                    <td class="p-3">
                        03
                    </td>
                </tr>

                <tr class="bg-gray-100">
                    <td class="p-3">
                        04
                    </td>
                </tr>

                <tr>
                    <td class="p-3">
                        05
                    </td>
                </tr>

                <tr class="bg-gray-100">
                    <td class="p-3">
                        06
                    </td>
                </tr>

                <tr>
                    <td class="p-3">
                        07
                    </td>
                </tr>

                <tr class="bg-gray-100">
                    <td class="p-3">
                        08
                    </td>
                </tr>
            </tbody>
        </table>

        @foreach($companies as $company)
        <table class="w-[{{ ((int) $company['colspan'] * 125) . 'px' }}]">
            <thead>
                <tr>
                    @foreach($company['labels'] as $label)
                    <th class="p-3 w-[125px]">
                        <span class="flex text-sm font-normal text-[#b1b1b1] justify-center">
                            {{ $label }}
                        </span>
                    </th>
                    @endforeach
                </tr>

                <tr class="bg-gray-100">
                    @foreach($company['prices'] as $price)
                    <th class="p-3">
                        <span class="flex text-[16px] font-normal text-[#b1b1b1] justify-center">
                           {{ $price }}
                        </span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($company['rows'] as $row)
                <tr class="{{ (int) $loop->index % 2 == 0 ? '' : 'bg-gray-100' }}">
                    @foreach($row as $value)
                    <td class="p-3">
                        <span class="flex justify-center">
                            {{ $value }}
                        </span>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

        {{-- <button class="flex justify-center items-center border-2 border-dashed w-[125px] rounded-xl ml-3">
            <span class="text-xl text-gray-200">
                +
            </span>
        </button> --}}
    </div>

</div>
