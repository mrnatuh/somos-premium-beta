<x-app-layout>
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

             <x-dashboard.bar :active="$active" />

            <ul class="mt-10 flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                @foreach($categories as $category)
                    @if($category['slug'] === $active)
                        <li class="mr-4">
                            <a
                                href="?filter={{ $category['slug'] }}"
                                class="text-lg inline-block p-1 bg-blue-600 text-gray-100 dark:bg-gray-800 dark:text-blue-500">{{ $category['label'] }}</a>
                        </li>
                    @else
                        <li class="mr-4">
                            <a href="?filter={{ $category['slug'] }}"
                            class="text-lg inline-block p-1 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300">{{ $category['label'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="w-full">
                <livewire:faturamento />
            </div>

            <div class="w-full flex items-center justify-end gap-4 p-6">
                <a href="{{ route('previa') }}"
                class="bg-green-600 px-6 py-2 text-white rounded-xl text-xl font-bold">
                    Salvar
                </a>

                <a href="{{ route('previa') }}" class="bg-red-600 px-6 py-2 text-white rounded-xl text-xl font-bold">
                    Cancelar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
