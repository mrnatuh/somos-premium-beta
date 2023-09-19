<div class="flex w-full gap-4">
    <x-dashboard.card label="Faturamento" :value="$total['faturamento']" :selected="$active == 'faturamento'" />
    <x-dashboard.card label="Eventos" :value="$total['events']" :selected="$active == 'eventos'" />
    <x-dashboard.card label="MP" :value="$total['mp']" :selected="$active == 'mp'" />
    <x-dashboard.card label="MO" :value="$total['mo']" :selected="$active == 'mo'" />
    <x-dashboard.card label="GD" :value="$total['gd']" :selected="$active == 'gd'" />
    <x-dashboard.card label="Investimento" :value="$total['investimento']" value-align="text-center" :selected="$active == 'investimento'" />
</div>
