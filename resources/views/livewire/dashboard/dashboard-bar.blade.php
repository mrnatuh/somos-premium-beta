<div class="space-y-3">
  <p class="flex items-center text-xs space-y-1 text-gray-400 italic px-3">
    ref. {{ $preview['cc'] ?? '' }} / {{ $preview['week_ref'] ?? '' }}
  </p>

  <div class="flex w-full gap-4">
    <x-dashboard.card
      label="Faturamento"
      :value="$total['faturamento']"
      :selected="$active == 'faturamento'"
      group="0001"
      :cc="$cc"
      :month_ref="$month_ref"
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
      :cc="$cc"
      :month_ref="$month_ref"
    />
  
    <x-dashboard.card
      label="MO"
      :value="$total['mo']"
      :selected="$active == 'mo'"
      group="0004"
      :cc="$cc"
      :month_ref="$month_ref"
    />
  
    <x-dashboard.card
      label="GD"
      :value="$total['gd']"
      :selected="$active == 'gd'"
      group="0005"
      :cc="$cc"
      :month_ref="$month_ref"
    />
  
    <x-dashboard.card
      label="Investimento"
      :value="$total['investimento']"
      :selected="$active == 'investimento'"
      group="0006"
      :cc="$cc"
      :month_ref="$month_ref"
    />
  </div>  
</div>
