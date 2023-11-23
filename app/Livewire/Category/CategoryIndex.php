<?php

namespace App\Livewire\Category;

use App\Models\Preview;
use Livewire\Component;

class CategoryIndex extends Component
{
    public function render()
    {
        $preview = Preview::where([
            'cc' => session('preview')['cc'],
            'week_ref' => session('preview')['week_ref'],
        ])->first();

        return view('livewire.category.category', [
            'cc' => session('preview')['cc'],
            'weekref' => session('preview')['week_ref'],
            'published_at' => $preview->published_at,
            'approved_at' => $preview->approved_at,
            'logs' => $preview->logs ? unserialize($preview->logs) : [],
        ]);
    }
}
