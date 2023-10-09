<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreviewEdit extends Controller
{
    public function __invoke(Request $request)
    {
        $cc = Auth::user()->cc ?? 1;

        session()->put('preview', [
            'cc' => $cc,
            'week_ref' => $request->input('weekref')
        ]);

        return to_route('category', [
            'filter' => 'faturamento'
        ]);
    }
}
