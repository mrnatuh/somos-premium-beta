<?php

namespace App\Livewire\Preview;

use App\Models\Preview;
use Livewire\Component;
use Livewire\Attributes\On;

class PreviewIndex extends Component
{
    public $previews = [];

    public $month_ref = '';

    #[On('update-month')]
    public function setMonthRef($month, $year)
    {
        $month += 1;
        $month = $month < 10 ? 0 . '' . $month : $month;
        $this->month_ref = $month . '_' . substr($year, -2);

        $this->render();
    }

    public function mount()
    {
        $this->month_ref = date('m') . '_' . substr(date('Y'), -2);
    }

    public function render()
    {
        $this->previews = Preview::where('client_id', 1)
            ->where('month_ref', $this->month_ref)
            ->get();

        $total = [
            'faturamento' => 0,
            'events' => 0,
            'mp' => 0,
            'mo' => 0,
            'gd' => 0,
            'investimento' => 0
        ];

        foreach ($this->previews as $preview) {
            $total['faturamento'] += $preview->invoicing ?? 0;
            $total['events'] += $preview->events ?? 0;
            $total['mp'] += $preview->mp ?? 0;
            $total['mo'] += $preview->mo ?? 0;
            $total['gd'] += $preview->gd ?? 0;
            $total['investimento'] += $preview->rou ?? 0;
        }

        return view('livewire.preview.index', [
            'previews' => $this->previews,
            'total' => $total,
        ]);
    }
}
