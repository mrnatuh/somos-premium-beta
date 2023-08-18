<x-app-layout>
    <div class="py-4">
        <div class="w-full mx-auto">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-5 gap-4">

                    <div class="grid-col-1  rounded-xl border bg-card text-card-foreground shadow">
                        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium">Faturamento</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="text-2xl font-bold">R$ 85.125,00</div>
                        </div>
                    </div>

                    <div class="grid-col-1  rounded-xl border bg-card text-card-foreground shadow">
                        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium">MP</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="text-2xl font-bold">R$ 23.158,00</div>
                        </div>
                    </div>

                    <div class="grid-col-1  rounded-xl border bg-card text-card-foreground shadow">
                        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium">GD</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="text-2xl font-bold">R$ 8.855,00</div>
                        </div>
                    </div>

                    <div class="grid-col-1  rounded-xl border bg-card text-card-foreground shadow">
                        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium">MO</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="text-2xl font-bold">R$ 12.123,00</div>
                        </div>
                    </div>

                    <div class="grid-col-1  rounded-xl border bg-card text-card-foreground shadow">
                        <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium">ROU</h3>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="text-2xl font-bold">49 %</div>
                        </div>
                    </div>

                </div>

            </div>

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

            <ul class="mt-10 ml-6 flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
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

            <div class="w-full p-6">
                @if($active === "eventos")
                    <table class="w-full p-6">
                        <thead>
                            <tr>
                                <th class="p-2 text-left text-slate-400">Cliente</th>
                                <th class="p-2 text-center text-slate-400">Quantidade</th>
                                <th class="p-2 text-center text-slate-400">Valor Unitário</th>
                                <th class="p-2 text-center text-slate-400">Valor Total</th>
                                <th class="p-2 text-center text-slate-400">Data Evento</th>
                                <th class="p-2 text-center text-slate-400">Data Faturamento</th>
                                <th class="p-2 text-center text-slate-400">Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 ">Mercado Livre</td>
                                <td class="p-2 text-center">100</td>
                                <td class="p-2 text-center">120,00</td>
                                <td class="p-2 text-center">1.200.000,00</td>
                                <td class="p-2 text-center">01/08/2023</td>
                                <td class="p-2 text-center">01/09/2023</td>
                                <td class="p-2 text-center">Festa Junina</td>
                            </tr>
                            <tr>
                                <td class="p-2">Graber</td>
                                <td class="p-2 text-center">30</td>
                                <td class="p-2 text-center">100,00</td>
                                <td class="p-2 text-center">3.000,00</td>
                                <td class="p-2 text-center">01/08/2023</td>
                                <td class="p-2 text-center">01/09/2023</td>
                                <td class="p-2 text-center">Dia Independência</td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                @if($active === "mp")
                    <table class="w-full p-6">
                        <thead>
                            <tr>
                                <th class="p-2 text-left text-slate-400">Item</th>
                                <th class="p-2 text-center text-slate-400">Número Pedido</th>
                                <th class="p-2 text-center text-slate-400">Tipo Pedido</th>
                                <th class="p-2 text-center text-slate-400">Grupo de Compras</th>
                                <th class="p-2 text-center text-slate-400">Data</th>
                                <th class="p-2 text-center text-slate-400">Valor</th>
                                <th class="p-2 text-center text-slate-400">Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 ">1</td>
                                <td class="p-2 text-center">4903</td>
                                <td class="p-2 text-center">Normal</td>
                                <td class="p-2 text-center">Limpeza</td>
                                <td class="p-2 text-center">01/08/2023</td>
                                <td class="p-2 text-center">644,77</td>
                                <td class="p-2 text-center"></td>
                            </tr>
                            <tr>
                                <td class="p-2">2</td>
                                <td class="p-2 text-center">5007</td>
                                <td class="p-2 text-center">Extra</td>
                                <td class="p-2 text-center">Estocáveis CD</td>
                                <td class="p-2 text-center">01/08/2023</td>
                                <td class="p-2 text-center">1.400,59</td>
                                <td class="p-2 text-center">Mercadoria Danificada</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
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
