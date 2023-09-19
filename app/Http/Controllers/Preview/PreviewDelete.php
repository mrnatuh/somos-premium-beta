<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Models\Preview;
use Illuminate\Http\Request;

class PreviewDelete extends Controller
{
    public function __invoke(Request $request)
    {
        $preview = Preview::where('id', $request->id)->first();

        $preview->delete();

        return to_route('preview');
    }
}
