<div class="w-full">
    <x-dashboard.container>
        <div class="flex border-b items-center justify-between pb-10 mb-10">
            <div>
                <strong class="text-2xl">Dashboard</strong>
                <p class="text-gray-600">1 resultado encontrado</p>
            </div>

            <x-notification.icon />
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
