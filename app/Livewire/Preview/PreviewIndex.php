<?php

namespace App\Livewire\Preview;

use App\Models\Preview;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewIndex extends Component
{
    public $previews = [];

    public $month = 0;

    #[On('update-month')]
    public function setMonth($month)
    {
        $this->month = $month + 1;
    }

    public function render()
    {
        if (!$this->month) {
            $this->month = (int) date('m') - 1;
        }

        $this->previews = Preview::where('client_id', 1)->get();

        return view('livewire.preview.index', [
            'previews' => $this->previews
        ]);
    }
}
