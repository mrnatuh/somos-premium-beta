<div class="flex flex-col mt-10 relative flex-1 w-full">
    <div class="grid grid-cols-6 w-full gap-10">
        <div class="col-span-1">
            <form class="flex flex-col gap-6" wire:submit="addClient">
                @csrf

                <div class="flex flex-col gap-1">
                    <label htmlFor="day">Dia</labeL>
                    <select id="day" wire:model="day"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value>Selecione</option>
                        @for($d = 1; $d <= $lastOfMonth; $d++) <option value="{{ $d }}">{{ $d < 10 ? '0' . $d : $d
                                }}</option>
                                @endfor
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label htmlFor="client">Cliente</labeL>
                    <select id="client" wire:model="client" @change="$dispatch('rest-client-selected')"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value>Selecione</option>
                        @foreach($clients as $client)
                        <option value="{{ trim($client->A1_COD) . "_" . trim($client->A1_CGC) }}">{{
                            trim($client->A1_NOME) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label htmlFor="client">Serviços</labeL>
                    <select id="service" wire:model="service"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value>Selecione</option>
                        @foreach($filtered_prices as $price)
                        <option value="{{ trim($price->DA1_CODPRO) . "_" . trim($price->DA0_CODTAB) . "_" .
                            trim($price->DA0_CC) }}">{{ trim($price->B1_DESC) }} R$ {{ trim($price->DA1_PRCVEN) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label htmlFor="qty">Resto (kg)</labeL>
                    <input id="qty" wire:model="qty" type="number" min="0.0" max="1000"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required />
                </div>

                <button type="submit"
                    class="py-2.5 px-5 mb-2 text-[16px] font-medium text-gray-900 focus:outline-none bg-white rounded-[10px] border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 cursor-pointer relative">
                    <x-status.loading />

                    Adicionar
                </button>
            </form>
        </div>

        <div class="col-span-5">
            @if (sizeof($results))
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="p-3 border-b text-center">
                            Dia
                        </th>
                        <th class="text-left p-3 border-b">
                            Cliente
                        </th>
                        <th class="text-left p-3 border-b">
                            Serviço
                        </th>
                        <th class="text-center p-3 border-b">
                            Resto (kg)
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr class="{{ $loop->index % 2 == 0 ? 'bg-slate-100' : '' }}">
                        <td class="p-3 text-center">
                            {{ $result['day'] < 10 ? '0' . $result['day'] : $result['day'] }}/{{ $month }} </td>
                        <td class="p-3">
                            {{ $result['client']['name'] }}<br />
                            <small class="text-gray-400">{{ $result['client']['cgc'] }}</small>
                        </td>
                        <td class="p-3">
                            {{ trim($result['service']->B1_DESC) }} R$ {{ trim($result['service']->DA1_PRCVEN) }}
                        </td>
                        <td class="p-3 text-center">
                            {{ $result['qty'] }}
                        </td>
                        <td>
                            <button wire:click="deleteClient({{ $loop->index }})">
                                <img src="img/delete.svg" />
                            </button>
                        </td>
                    </tr>
                    @endforeach
                <tfoot>
                    <tr class="border-t">
                        <td class="p-3">

                        </td>
                        <td class="p-3">

                        </td>
                        <td class="p-3">
                        </td>
                        <td class="text-center p-3 font-bold">
                            {{ $total_qty }} kg
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                </tbody>
            </table>
            @else
            <p class="text-gray-500 text-center px-6 py-20">Nenhum resultado encontrado.</p>
            @endif
        </div>
    </div>

    <div class="h-20"></div>
</div>
