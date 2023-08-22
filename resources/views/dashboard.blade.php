<x-app-layout>
    <div class="py-4">
        <x-dashboard.container>
            <div class="mb-10">
                <strong class="text-2xl">Dashboard</strong>
                <p class="text-gray-600">18 resultados encontradas</p>
            </div>

            <livewire:dashboard-bar />

            @if($previas)
            <x-table.silver
                :columns="$previas['columns'] ?? []"
                :rows="$previas['rows'] ?? []"
            />
            @endif
        </x-dashboard.container>
    </div>
</x-app-layout>
