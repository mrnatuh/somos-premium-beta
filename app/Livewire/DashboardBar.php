<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class DashboardBar extends Component
{
    public $active = '';

    public $total = [
        'faturamento' => 'R$ 85.125,00',
    ];

    #[On('update-bar-total')]
    public function updateBarTotal($label, $value)
    {
        $this->total[$label] = 'R$ ' . number_format($value, 2, ',', '.');
    }

    public function render()
    {
        return view('livewire.dashboard-bar');
    }
}
