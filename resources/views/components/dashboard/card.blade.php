@props([
    'label' =>  '',
    'value' => 0.00,
    'valueAlign' => '',
    'arrow' => '',
    'selected' => false,
    'type' => 'money',
    'group' => '',
	'cc' => '',
	'month_ref' => '',
])

<div class="dashboard-card rounded-xl border bg-card text-card-foreground shadow {{ $selected ? 'bg-blue-500': '' }}" data-group="{{ $group }}">
    <div class="p-4 flex items-center gap-2 pb-2">
        <h3 class="tracking-tight text-sm {{ $selected ? 'text-white' : 'text-[#757D8A]' }} font-medium">{{ $label }}</h3>
    </div>

    <div class="p-4 pt-0 pb-0 {{ $valueAlign }}">
        <p class="flex items-center gap-1 text-lg font-bold whitespace-nowrap {{ $selected ? 'text-white' : 'text-[#404D61]' }}">
		{{ $type == 'money' ? 'R$ ' . number_format((float) $value, 2, ',', '.') : $value . '%' }}

		@if($group)
		<span data-bar-arrow='{"cc":"{{ $cc }}","month_ref":"{{ $month_ref }}","group":"{{$group}}","total":{{ (float) $value }}}'></span>
		@endif
		</p>
    </div>

		@if($group)
		<div 
			class="p-4 pt-0 text-gray-400 text-xs" 
			data-bar-total='{"cc":"{{ $cc }}","month_ref":"{{ $month_ref }}","group":"{{$group}}"}'
		>
			(R$ 0,00)
		</div>
		@endif
</div>
