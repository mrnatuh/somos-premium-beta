<?php

namespace App\Livewire\Preview;

use App\Models\LinkUser;
use App\Models\Preview;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class PreviewIndex extends Component
{
    public $previews = [];

    public $month_ref = '';

    #[On('update-month')]
    public function setMonthRef($month, $year)
    {
        $selectedMonth = $month + 1;
        $selectedMonth = $selectedMonth < 10 ? 0 . '' . $selectedMonth : $selectedMonth;
        $this->month_ref = $selectedMonth . '_' . substr($year, -2);

        //$this->render();
    }

    public function mount()
    {
        $this->month_ref = date('m') . '_' . substr(date('Y'), -2);
    }

    public function render()
    {

        $access = Auth::user()->access;
        $cc = Auth::user()->cc ?? false;

        $user_links = LinkUser::all();

        $ccs = [];
        $supervisors = [];

        // regra para diretores
        if ($access === 'director') {
            foreach ($user_links as $link) {
                if ($link->user_id == Auth::user()->id) {
                    array_push($supervisors, $link->parent_id);
                }
            }

            foreach ($user_links as $link) {
                if (in_array($link->user_id, $supervisors)) {
                    array_push($ccs, $link->parent_id);
                }
            }

            $this->previews = Preview::where('month_ref', '=', $this->month_ref)->whereIn('cc', $ccs)->get();
            // regra para coordenadores
        } else if ($access === 'coordinator') {
            foreach ($user_links as $link) {
                if ($link->user_id == Auth::user()->id) {
                    array_push($ccs, $link->parent_id);
                }
            }

            $this->previews = Preview::where('month_ref', '=', $this->month_ref)->whereIn('cc', $ccs)->get();

            // regra para supervisores
        } else if ($cc) {
            $this->previews = Preview::where([
                ['cc', '=', $cc],
                ['month_ref', '=', $this->month_ref]
            ])->get();
        } else {
            $this->previews = Preview::where('month_ref', $this->month_ref)->get();
        }

        $total = [
            'faturamento' => 0,
            'events' => 0,
            'mp' => 0,
            'mo' => 0,
            'gd' => 0,
            'he' => 0,
            'investimento' => 0
        ];

        // foreach ($this->previews as $preview) {
        //     $total['faturamento'] += $preview->invoicing ?? 0;
        //     $total['events'] += $preview->events ?? 0;
        //     $total['mp'] += $preview->mp ?? 0;
        //     $total['mo'] += $preview->mo ?? 0;
        //     $total['gd'] += $preview->gd ?? 0;
        //     $total['investimento'] += $preview->rou ?? 0;
        // }

        return view('livewire.preview.index', [
            'previews' => $this->previews,
            'total' => $total,
        ]);
    }
}
