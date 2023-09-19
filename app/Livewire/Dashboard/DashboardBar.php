<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\On;

class DashboardBar extends Component
{

    public $active = '';

    public $total = [
        'faturamento' => 'R$ 0,00',
        'events' => 'R$ 0,00',
        'mp' => 'R$ 0,00',
        'mo' => 'R$ 0,00',
        'gd' => 'R$ 0,00',
        'investimento' => '0 %'
    ];

    #[On('update-bar-total')]
    public function updateBarTotal($label, $value)
    {
        if ($label != 'investimento') {
            $this->total[$label] = 'R$ ' . number_format($value, 2, ',', '.');
        } else {
            $this->total[$label] = $value;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-bar');
    }
}
