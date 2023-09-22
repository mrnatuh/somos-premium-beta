<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\On;

class DashboardBar extends Component
{

    public $active = '';

    public $total = [
        'faturamento' => 0,
        'events' => 0,
        'mp' => 0,
        'mo' => 0,
        'gd' => 0,
        'investimento' => 0
    ];

    #[On('update-bar-total')]
    public function updateBarTotal($label, $value)
    {
        $this->total[$label] = $value;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-bar');
    }
}
