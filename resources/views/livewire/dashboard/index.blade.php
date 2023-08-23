<div class="py-4">
    <x-dashboard.container>
        <div class="mb-10">
            <strong class="text-2xl">Dashboard</strong>
            <p class="text-gray-600">18 resultados encontradas</p>
        </div>

        <livewire:dashboard.dashboard-bar />

        @if($preview)
        <x-table.silver
            :columns="$preview['labels'] ?? []"
            :rows="$preview['rows'] ?? []"
        />
        @endif
    </x-dashboard.container>
</div>
