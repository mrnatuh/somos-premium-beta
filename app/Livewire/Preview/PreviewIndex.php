<?php

namespace App\Livewire\Preview;

use App\Models\LinkUser;
use App\Models\Preview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\On;

class PreviewIndex extends Component
{
	public $previews = [];

	public $month_ref = '';

	public $orcamento = [];

	#[On('update-month')]
	public function setMonthRef($month, $year)
	{
		$selectedMonth = $month + 1;
		$selectedMonth = $selectedMonth < 10 ? 0 . '' . $selectedMonth : $selectedMonth;
		$this->month_ref = $selectedMonth . '_' . substr($year, -2);

		//session('preview')['week_ref'] = $this->month_ref;
	}

	public function mount(Request $request)
	{
		$req_month_ref = $request->input('month_ref');
		if (!empty(trim($req_month_ref))) {
			$split = explode("_", $req_month_ref);
			if (isset($split[0]) && isset($split[1])) {
				$this->month_ref = "{$split[0]}_{$split[1]}";
			}
		} else {
			$this->month_ref = date('m') . '_' . substr(date('Y'), -2);
		}
		//session('preview')['week_ref'] = $this->month_ref;
	}

	public function render()
	{
		$cc = Auth::user()->cc ?? false;

		$access = Auth::user()->access;

		$user_links = LinkUser::all();

		$ccs = [];
		$supervisors = [];

		// regra para diretores
		if ($access === 'director') {
			foreach ($user_links as $link) {
				if ($link->user_id == Auth::user()->id) {
					array_push($supervisors, $link->parent_id);
				}
			}

			foreach ($user_links as $link) {
				if (in_array($link->user_id, $supervisors)) {
					array_push($ccs, $link->parent_id);
				}
			}

			$this->previews = Preview::where('month_ref', '=', $this->month_ref)->whereIn('cc', $ccs)->get();
			// regra para coordenadores
		} else if ($access === 'coordinator') {
			foreach ($user_links as $link) {
				if ($link->user_id == Auth::user()->id) {
					array_push($ccs, $link->parent_id);
				}
			}

			$this->previews = Preview::where('month_ref', '=', $this->month_ref)->whereIn('cc', $ccs)->get();

			// regra para supervisores
		} else if ($cc) {
			$this->previews = Preview::where([
				['cc', '=', $cc],
				['month_ref', '=', $this->month_ref]
			])->get();
		} else {
			$this->previews = Preview::where('month_ref', $this->month_ref)->get();
		}

		return view('livewire.preview.index', [
			'previews' => $this->previews,
			'realizadas' => 0,
		]);
	}
}
