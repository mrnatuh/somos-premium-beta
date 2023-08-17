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

            <div class="w-full p-6">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&amp;_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 w-[100px]">
                                Previa</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Faturamento</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                MP</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                MO</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                GD</th>
                             <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                ROU</th>
                             <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Status</th>
                             <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Variação</th>
                             <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                &nbsp;</th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">070423</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 63.550,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 3.500,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 7.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 1.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">40%</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-blue-500 text-center">Validado</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">0,79%</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">
                                <div class="flex gap-3">
                                    <button>
                                        <img src="/img/edit.svg" alt="Edit" />
                                    </button>
                                    <button>
                                        <img src="/img/delete.svg" alt="Delete" />
                                    </button>

                                </div>

                            </td>
                        </tr>
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">070323</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 63.550,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 3.500,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 7.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 1.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">40%</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-red-500 text-center">Recusado</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">0,79%</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">
                                <div class="flex gap-3">
                                    <button>
                                        <img src="/img/edit.svg" alt="Edit" />
                                    </button>
                                    <button>
                                        <img src="/img/delete.svg" alt="Delete" />
                                    </button>

                                </div>

                            </td>
                        </tr>
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 font-medium">070223</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 63.550,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0">R$ 3.500,30</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 7.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">R$ 1.500,00</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">40%</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-orange-500 text-center">Em análise</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">-</td>
                            <td class="p-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-right">
                                <div class="flex gap-3">
                                    <button>
                                        <img src="/img/edit.svg" alt="Edit" />
                                    </button>
                                    <button>
                                        <img src="/img/delete.svg" alt="Delete" />
                                    </button>

                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
