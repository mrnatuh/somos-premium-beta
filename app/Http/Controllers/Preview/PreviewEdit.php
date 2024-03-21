<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreviewEdit extends Controller
{
	public function __invoke(Request $request)
	{
		$cc = Auth::user()->cc ? Auth::user()->cc : false;

		if (!$cc) {
			$cc = $request->input('cc');
		}

		if ($cc) {
			$realizadas = (int) $request->input('realizadas');
			
			session()->put('preview', [
				'cc' => $cc,
				'week_ref' => $request->input('weekref'),
				'realizadas' => $realizadas,
			]);

			return redirect()->to("/categoria?filter=faturamento");
		}
	}
}
