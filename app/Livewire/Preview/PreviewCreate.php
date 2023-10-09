<?php

namespace App\Livewire\Preview;

use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PreviewCreate extends Component
{
    public $year = 0;
    public $month = 0;
    public $week = 0;
    public $day = 0;
    public $dt = 0;

    public $years = [
        '2023', '2024',
        '2025', '2026',
        '2027', '2028',
        '2029',
        '2030',
    ];

    public $months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Junho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];

    public $weeks = 0;

    public function weekOfMonth($when = null)
    {
        if ($when === null) $when = time();
        $week = date('W', $when); // note that ISO weeks start on Monday
        $firstWeekOfMonth = date('W', strtotime(date('Y-m-01', $when)));
        return 1 + ($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth);
    }

    public function getWeeks()
    {
        $this->dt = Carbon::createFromDate($this->year, $this->month + 1, 1);
        $this->weeks = $this->dt->endOfMonth()->weekOfMonth;
    }

    public function mount()
    {
        $this->day = (int) date('d');
        $this->year = (int) date('Y');
        $this->month = (int) date('m') - 1;

        $this->dt = Carbon::createFromDate($this->year, $this->month + 1, 1);
        $this->weeks = $this->dt->endOfMonth()->weekOfMonth;
        $this->week = $this->weekOfMonth();
    }

    public function save()
    {
        $week = 0 . '' . $this->week;

        $month = $this->month + 1;
        $month = $month < 10 ? 0 . '' . $month : $month;

        $year = substr($this->year, -2);

        $weekref = "{$month}{$week}{$year}";
        $monthref = "{$month}_{$year}";

        $cc = Auth::user()->cc ?? 1;

        $preview = Preview::firstOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref
            ],
            [
                'cc' => $cc,
                'month_ref' => $monthref
            ],
        )->save();

        session()->put('preview', [
            'cc' => $cc,
            'week_ref' => $weekref
        ]);

        return to_route('category', [
            'filter' => 'faturamento',
        ]);
    }

    public function render()
    {
        return view('livewire.preview.preview-create');
    }
}
