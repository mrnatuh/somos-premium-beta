<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PreviewCreate extends Controller
{
    public function weekOfMonth($when = null)
    {
        if ($when === null) $when = time();
        $week = date('W', $when); // note that ISO weeks start on Monday
        $firstWeekOfMonth = date('W', strtotime(date('Y-m-01', $when)));
        return 1 + ($week < $firstWeekOfMonth ? $week : $week - $firstWeekOfMonth);
    }

    public function __invoke(Request $request)
    {
        $week = 0 . '' . $this->weekOfMonth();
        $weekref = Carbon::now()->format("m{$week}y");

        $preview = Preview::firstOrCreate(
            ['week_ref' => $weekref],
            ['client_id' => 1],
        )->save();

        return to_route('category', ['client_id' => 1, 'week_ref' => $weekref]);
    }
}
