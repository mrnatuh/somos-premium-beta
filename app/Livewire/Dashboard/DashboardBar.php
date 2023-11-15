<?php

namespace App\Livewire\Dashboard;

use App\Models\Preview;
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

    public function getTotals()
    {
        $cc = session('preview')['cc'] ?? false;
        $weekref = session('preview')['week_ref'] ?? false;

        if ($cc) {
            $preview = Preview::where([
                ['cc', '=', $cc],
                ['week_ref', '=', $weekref]
            ])->first();

            if ($preview) {
                $this->total['faturamento'] = $preview->invoicing;
                $this->total['events'] = $preview->events;
                $this->total['mp'] = $preview->mp;
                $this->total['mo'] = $preview->mo;
                $this->total['gd'] = $preview->gd;
                $this->total['investimento'] = $preview->rou;
            }
        }
    }

    #[On('update-bar-total')]
    public function updateBarTotal()
    {
        $this->getTotals();
    }

    public function mount()
    {
        $this->getTotals();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-bar');
    }
}
