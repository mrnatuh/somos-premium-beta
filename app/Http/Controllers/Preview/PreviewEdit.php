<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreviewEdit extends Controller
{
	public function __invoke(Request $request)
	{
		$cc = Auth::user()->cc ?? false;

		if (!$cc) {
			$cc = $request->input('cc');
		}

		if ($cc) {
			session()->put('preview', [
				'cc' => $cc,
				'week_ref' => $request->input('weekref'),
				'realizadas' => $request->input('realizadas'),
			]);

			return redirect()->to('/categoria?filter=faturamento');
		}
	}
}
