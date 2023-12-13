<div class="flex w-full gap-4">
    <x-dashboard.card
        label="Faturamento"
        :value="$total['faturamento']"
        :selected="$active == 'faturamento'"
        group="0001"
    />

    <x-dashboard.card
        label="Eventos"
        :value="$total['events']"
        :selected="$active == 'eventos'"
    />

    <x-dashboard.card
        label="MP"
        :value="$total['mp']"
        :selected="$active == 'mp'"
        group="0003"
    />

    <x-dashboard.card
        label="MO"
        :value="$total['mo']"
        :selected="$active == 'mo'"
        group="0004"
    />

    <x-dashboard.card
        label="GD"
        :value="$total['gd']"
        :selected="$active == 'gd'"
        group="0005"
    />

    <x-dashboard.card
        label="Investimento"
        :value="$total['investimento']"
        value-align="text-center"
        :selected="$active == 'investimento'"
        group="0006"
    />
</div>
