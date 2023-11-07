@props([
    'type' => 'info',
    'message' => ''
])

@php
$messageType = [
    'info' => 'border border-blue-500 text-blue-800 bg-blue-50 dark:bg-gray-800 dark:text-blue-400',
    'danger' => 'border border-red-500 text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400',
    'success' => 'border border-green-500 text-green-800 bg-green-50 dark:bg-gray-800 dark:text-green-400',
    'warning' => 'border border-yellow-500 text-yellow-800 bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300',
    'dark' => 'border border-gray-500 text-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-gray-300',
][$type];
@endphp

<div class="alert flex items-center justify-center absolute -top-10 right-8 z-50 -translate-y-full absolute">
    <div class="flex py-3 px-5 mb-4 text-lg rounded-lg {{ $messageType }}" role="alert">
        {{ $message }}
    </div>
</div>
