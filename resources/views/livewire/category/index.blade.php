<div class="py-4">
    <div class="w-full mx-auto p-8">


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
        @endphp

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

        <div class="w-full">
            <livewire:category.category-invoicing />
        </div>
    </div>
</div>
