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

        $logs = isset($preview->logs) ? unserialize($preview->logs) : [];
        $last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : [];

        return view('livewire.category.category', [
            'id' => $preview->id,
            'cc' => session('preview')['cc'],
            'weekref' => session('preview')['week_ref'],
            'published_at' => $preview->published_at,
            'approved_at' => $preview->approved_at,
            'logs' => $logs,
            'last_log' => $last_log,
        ]);
    }
}
