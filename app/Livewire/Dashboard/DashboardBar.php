<?php

namespace App\Livewire\Dashboard;

use App\Models\Preview;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\On;

class DashboardBar extends Component
{

	public $active = '';
	public $cc = null;
	public $month_ref = null;
	public $week_ref = null;
	public $inside = false;

	public $total = [
		'faturamento' => 0,
		'events' => 0,
		'mp' => 0,
		'mo' => 0,
		'gd' => 0,
		'investimento' => 0
	];

	public function getTotals()
	{
		$preview = null;

		// logged user cc
		if ($this->cc && $this->inside) {
			$preview = Preview::where([
				['cc', '=', $this->cc],
				['week_ref', '=', $this->week_ref],
			])->orderBy('updated_at', 'desc')
				->first();
		} else if ($this->cc) {
			$preview = Preview::where([
				['cc', '=', $this->cc],
				['month_ref', '=', $this->month_ref],
				['status', '=', 'validado'],
			])->orderBy('updated_at', 'desc')
				->first();
			// any one
		} else {
			$preview = Preview::where([
				['month_ref', '=', $this->month_ref],
				['status', '=', 'validado'],
			])->orderBy('updated_at', 'desc')
				->first();
		}

		if ($preview) {
			$this->total['faturamento'] = $preview->invoicing;
			$this->total['events'] = $preview->events;
			$this->total['mp'] = $preview->mp;
			$this->total['mo'] = $preview->mo;
			$this->total['gd'] = $preview->gd;
			$this->total['investimento'] = $preview->rou;
		} else {
			$this->total['faturamento'] = 0;
			$this->total['events'] = 0;
			$this->total['mp'] = 0;
			$this->total['mo'] = 0;
			$this->total['gd'] = 0;
			$this->total['investimento'] = 0;
		}
	}

	#[On('update-bar-total')]
	public function updateBarTotal()
	{
		$session_preview = session("preview") ?? null;
		if ($session_preview) {
			if (isset($session_preview['cc'])) {
				$this->cc = $session_preview['cc'];
			}

			if (isset($session_preview['week_ref'])) {
				$this->week_ref = $session_preview['week_ref'];
			}

			$this->inside = true;

			$this->getTotals();
		}
	}

	#[On('update-month')]
	public function getTotalFromMonth($month, $year)
	{
		// when update month, change month_ref
		$selectedMonth = $month + 1;
		$selectedMonth = $selectedMonth < 10 ? 0 . '' . $selectedMonth : $selectedMonth;

		$this->month_ref = $selectedMonth . '_' . substr($year, -2);

		$this->getTotals();
	}

	public function mount(Request $request)
	{
		// get cc from user or false (manager, admin, etc)
		$this->cc = auth()->user()->cc ?? false;

		// get current month
		$req_month_ref = $request->input('month_ref');
		if (!empty(trim($req_month_ref))) {
			$split = explode("_", $req_month_ref);
			if (isset($split[0]) && isset($split[1])) {
				$this->month_ref = "{$split[0]}_{$split[1]}";
			}
		} else {
			$this->month_ref = date('m') . '_' . substr(date('Y'), -2);
		}

		$this->getTotals();
	}

	public function render()
	{
		return view('livewire.dashboard.dashboard-bar');
	}
}
