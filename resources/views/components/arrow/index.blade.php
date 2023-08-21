@props([
    'arrow' => ''
])

@if($arrow == "up")
    <x-arrow.up />
@elseif($arrow == "down")
    <x-arrow.down />
@elseif($arrow == "up-blue")
    <x-arrow.up-blue />
@elseif($arrow == "down-white")
    <x-arrow.down-white />
@elseif($arrow == "down-silver")
    <x-arrow.down-silver />
@elseif($arrow == "down-green")
    <x-arrow.down-green />
@endif
