<div class="grid grid-cols-6 gap-4">
    <x-dashboard.card label="Faturamento" :value="$total['faturamento']" arrow="down-white" :selected="$active == 'faturamento'" />
    <x-dashboard.card label="Eventos" :value="$total['events']" :selected="$active == 'eventos'" />
    <x-dashboard.card label="MP" value="R$ 8.855,00" arrow="down" :selected="$active == 'mp'" />
    <x-dashboard.card label="MO" value="R$ 2.123,00" arrow="up-blue" :selected="$active == 'mo'" />
    <x-dashboard.card label="GD" value="R$ 123,00" arrow="up-blue" :selected="$active == 'gd'" />
    <x-dashboard.card label="Investimento" value="49 %" value-align="text-center" arrow="up-blue" :selected="$active == 'investimento'" />
</div>
