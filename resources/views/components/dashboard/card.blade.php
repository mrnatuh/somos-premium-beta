@props([
    'label' =>  '',
    'value' => '',
    'valueAlign' => '',
    'arrow' => '',
    'selected' => false,
])

<div class="grid-col-1 rounded-xl border bg-card text-card-foreground shadow {{ $selected ? 'bg-blue-500': '' }}">
    <div class="p-6 flex items-center gap-2 pb-2">
        <h3 class="tracking-tight text-lg {{ $selected ? 'text-white' : 'text-[#757D8A]' }} font-medium">{{ $label }}</h3>
        <x-arrow :arrow="$arrow" />
    </div>
    <div class="p-6 pt-0 {{ $valueAlign }}">
        <div class="text-[22px] font-bold {{ $selected ? 'text-white' : 'text-[#404D61]' }}">{{ $value }}</div>
    </div>
</div>
