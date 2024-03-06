<?php

namespace App\Livewire\Preview;

use App\Models\LinkUser;
use App\Models\Preview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PreviewCreate extends Component
{
	public $year = 0;
	public $month = 0;
	public $week = 0;
	public $day = 0;
	public $dt = 0;
	public $ccs = [];
	public $cc = false;

	public $years = [
		'2023', '2024',
		'2025', '2026',
		'2027', '2028',
		'2029',
		'2030',
	];

	public $months = [
		'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Junho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
	];

	public $weeks = 0;

	public function weekOfMonth($when = null)
	{
		if ($when === null) $when = time();
		$week = date('W', $when); // note that ISO weeks start on Monday
		$firstWeekOfMonth = date('W', strtotime(date('Y-m-01', $when)));

		return $week < $firstWeekOfMonth ? 1 + $week : $week - $firstWeekOfMonth;
	}

	public function getWeeks()
	{
		$this->dt = Carbon::createFromDate($this->year, $this->month + 1, 1);
		$this->weeks = $this->dt->endOfMonth()->weekOfMonth;
	}

	public function mount()
	{
		$this->day = (int) date('d');
		$this->year = (int) date('Y');
		$this->month = (int) date('m') - 1;

		$this->dt = Carbon::createFromDate($this->year, $this->month + 1, 1);
		$this->weeks = $this->dt->endOfMonth()->weekOfMonth;
		$this->week = $this->weekOfMonth();

		$cc = Auth::user()->cc ?? false;
		$access = Auth::user()->access;

		if (!$cc) {
			if ($access === 'admin' || $access == 'manager') {
				$this->ccs = User::where('access', '=', 'user')->get();
			} else if ($access === 'director') {
				$this->ccs = [];

				$supervisors = [];

				$user_links = LinkUser::all();

				foreach ($user_links as $link) {
					if ($link->user_id == Auth::user()->id) {
						array_push($supervisors, $link->parent_id);
					}
				}

				$ids = [];
				foreach ($user_links as $link) {
					if (in_array($link->user_id, $supervisors)) {
						array_push($ids, $link->parent_id);
					}
				}

				$this->ccs = User::whereIn('id', $ids)->get();
			} else if ($access === 'coordinator') {
				$this->ccs = [];

				$ids = LinkUser::where('user_id', '=', Auth::user()->id)->pluck('parent_id');

				$this->ccs = User::whereIn('id', $ids)->get();
			}
		}
	}

	public function save()
	{
		$week = 0 . '' . $this->week;

		$month = $this->month + 1;
		$month = $month < 10 ? 0 . '' . $month : $month;

		$year = substr($this->year, -2);

		$weekref = "{$month}{$week}{$year}";
		$monthref = "{$month}_{$year}";

		$cc = Auth::user()->cc ?? false;

		if (!$cc) {
			if (!$this->cc) {
				return back()->with('error', 'Selecione um centro de custo');
			}
		} else {
			$this->cc = $cc;
		}

		$preview = Preview::firstOrCreate(
			[
				'cc' => $this->cc,
				'week_ref' => $weekref
			],
			[
				'cc' => $this->cc,
				'month_ref' => $monthref
			],
		)->save();

		session()->put('preview', [
			'cc' => $this->cc,
			'week_ref' => $weekref,
			'realizadas' => 0,
		]);

		return $this->redirect('/categoria');
	}

	public function render()
	{
		return view('livewire.preview.preview-create');
	}
}
