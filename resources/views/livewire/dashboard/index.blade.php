<div class="w-full flex flex-col">
    <x-dashboard.container>
        <div class="flex border-b items-center justify-between pb-10 mb-10">
            <div>
                <strong class="text-2xl">Dashboard</strong>
            </div>

            <livewire:notifications />
        </div>
        
        <livewire:dashboard.dashboard-bar />
    </x-dashboard.container>
</div>

<script>
  fetch('/import/users', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    }
  });
</script>