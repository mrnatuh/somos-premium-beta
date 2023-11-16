<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Preview;
use Illuminate\Http\Request;

class MoSaveController extends Controller
{
    public function __invoke(Request $request)
    {
        $tmp_content = json_decode($request->input('mo_json'));

        $cc = $tmp_content->cc;
        $weekref = $tmp_content->weekref;

        // serializa o conteúdo
        $content = serialize($tmp_content);

        $total = isset($tmp_content->total) ? $tmp_content->total : 0;

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'mo',
            ],
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'mo',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        // acha o preview e seta o total
        $preview = Preview::where([
            ['cc', '=', $cc],
            ['week_ref', '=', $weekref]
        ])->first();

        $preview->mo = $total;
        $preview->save();

        return response()->json([
            'cc' => $cc,
            'week_ref' => $weekref,
            'option_name' => 'mo',
            'total' => $total,
        ]);
    }
}
