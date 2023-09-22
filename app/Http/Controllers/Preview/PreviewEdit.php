<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PreviewEdit extends Controller
{
    public function __invoke(Request $request)
    {
        session()->put('preview', [
            'client_id' => 1,
            'week_ref' => $request->input('weekref')
        ]);

        return to_route('category', [
            'filter' => 'faturamento'
        ]);
    }
}
