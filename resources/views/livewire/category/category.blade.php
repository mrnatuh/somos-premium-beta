@php
$categories = [
    ["slug" => "faturamento", "label" => "Faturamento"],
    ["slug" => "eventos", "label" => "Eventos"],
    ["slug" => "mp", "label" => "MP"],
    ["slug" => "mo", "label" => "MO"],
    ["slug" => "gd", "label" => "GD"],
    ["slug" => "investimento", "label" => "Investimento"],
];

$active = $_GET['filter'] ?? "faturamento";

$category = array_values(array_filter($categories, function($v, $k) use($active) {
    return $v['slug'] === $active;
}, ARRAY_FILTER_USE_BOTH))[0];
@endphp

<div class="flex flex-col w-full h-full p-8">

    <livewire:category.category-header :title="'Inclusão de '. $category['label']" />

    <livewire:dashboard.dashboard-bar :active="$active" />

    <ul class="mt-10 flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
        @foreach($categories as $category)
            <li class="mr-4">
                <a
                    href="?filter={{ $category['slug'] }}"
                    wire:navigate
                    class="text-lg inline-block p-2 px-3 rounded-lg    {{ $category['slug'] === $active ? 'bg-blue-500  hover:bg-blue-500 text-gray-100' : 'hover:bg-gray-50 hover:text-gray-600' }}"
                >{{ $category['label'] }}</a>
            </li>
        @endforeach
    </ul>

    <div class="flex flex-col w-full h-full">
        @if($active === 'faturamento')
        <livewire:category.category-invoicing />
        @endif

        @if($active === 'eventos')
        <livewire:category.category-event />
        @endif

        @if($active === 'mp')
        <livewire:category.category-m-p />
        @endif

        @if($active === 'mo')
        <livewire:category.category-m-o />
        @endif

        @if($active === 'gd')
        <livewire:category.category-g-d />
        @endif

        @if($active === 'investimento')
        <livewire:category.category-investimento />
        @endif
    </div>
</div>
