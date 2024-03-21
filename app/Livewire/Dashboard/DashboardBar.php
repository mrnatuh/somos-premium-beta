<?php

namespace App\Livewire\Dashboard;

use App\Helpers\UserRole;
use App\Models\Preview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class DashboardBar extends Component
{

	public $active = '';
	public $uri = '';
	public $cc = null;
	public $month_ref = null;
	public $week_ref = null;
	public $inside = false;
	public $preview = null;

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
		$ccs = (new UserRole())->getCc();
		
		// logged user ccs
		$preview = null;
		if ($this->uri === "categoria") {
			$sess = session('preview');
			$preview = Preview::where('cc', $sess['cc'])
				->where('week_ref', $sess['week_ref'])
				->first();
		
		} else {
			$preview = Preview::where('status', 'validado')
				->whereIn('cc', $ccs)
				->orderBy('updated_at', 'desc')
				->first();
		}
			
		if ($preview) {
			$this->cc = $preview->cc;
			$this->month_ref = $preview->month_ref;
			
			$this->total['faturamento'] = $preview->invoicing;
			$this->total['events'] = $preview->events;
			$this->total['mp'] = $preview->mp;
			$this->total['mo'] = $preview->mo;
			$this->total['gd'] = $preview->gd;
			$this->total['investimento'] = $preview->rou;

			$this->preview = $preview;
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
		$this->uri = $request->path();

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
		// renderiza o gasto previsto
		// $this->dispatch('render-preview-month');

		return view('livewire.dashboard.dashboard-bar', [
			'preview' => $this->preview,
		]);
	}
}
