<div class="grid grid-cols-6 gap-4">
    <x-dashboard.card label="Faturamento" :value="$total['faturamento']" arrow="down-white" :selected="$active == 'faturamento'" />
    <x-dashboard.card label="Eventos" :value="$total['events']" :selected="$active == 'eventos'" />
    <x-dashboard.card label="MP" :value="$total['mp']" arrow="down" :selected="$active == 'mp'" />
    <x-dashboard.card label="MO" :value="$total['mo']" arrow="up-blue" :selected="$active == 'mo'" />
    <x-dashboard.card label="GD" :value="$total['gd']" arrow="up-blue" :selected="$active == 'gd'" />
    <x-dashboard.card label="Investimento" :value="$total['investimento']" value-align="text-center" arrow="up-blue" :selected="$active == 'investimento'" />
</div>
