<?php

namespace App\Http\Controllers;

use App\Models\Option;
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
                'total' => 0,
            ]
        );

        return redirect('/categoria?filter=mo')->with('success', 'MO salva com sucesso');
    }
}
