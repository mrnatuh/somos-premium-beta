@props([
    "columns" => [],
    "rows" => []
])

<table class="w-full mt-10">
    <thead>
        <tr>
            @foreach($columns as $title)
                <th class="font-normal text-md text-gray-400 min-w-[150px] p-4">
                    <div class="flex items-center gap-3 {{ $title['align'] ?? '' }}">
                        {{ $title['label'] }}
                        <x-arrow arrow="down-silver" />
                    </div>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            @foreach($row as $td)
                <td class="bg-gray-100 p-4">
                    <span class="flex items-center text-[16px] text-[#404D61] gap-1 {{ $td['align'] ?? '' }}">
                        {{ $td['value'] }}

                        @if(isset($td['arrow']))
                        <x-arrow :arrow="$td['arrow']" />
                        @endif
                    </span>
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
