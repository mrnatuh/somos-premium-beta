@props([
    'label' =>  '',
    'value' => 0.00,
    'valueAlign' => '',
    'arrow' => '',
    'selected' => false,
    'type' => 'money',
    'group' => '',
])

<div class="dashboard-card rounded-xl border bg-card text-card-foreground shadow {{ $selected ? 'bg-blue-500': '' }}" data-group="{{ $group }}">
    <div class="p-4 flex items-center gap-2 pb-2">
        <h3 class="tracking-tight text-sm {{ $selected ? 'text-white' : 'text-[#757D8A]' }} font-medium">{{ $label }}</h3>
    </div>

    <div class="p-4 pt-0 {{ $valueAlign }}">
        <p class="text-lg font-bold whitespace-nowrap {{ $selected ? 'text-white' : 'text-[#404D61]' }}">{{ $type == 'money' ? 'R$ ' . number_format((float) $value, 2, ',', '.') : $value . '%' }}</p>
    </div>
</div>
