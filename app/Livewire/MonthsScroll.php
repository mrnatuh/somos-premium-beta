<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\Component;

class MonthsScroll extends Component
{
	protected $listeners = ['update-month' => '$refresh'];

	public $months = [
		[
			'label' => 'Janeiro'
		],
		[
			'label' => 'Fevereiro'
		],
		[
			'label' => 'Março'
		],
		[
			'label' => 'Abril'
		],
		[
			'label' => 'Maio'
		],
		[
			'label' => 'Junho'
		],
		[
			'label' => 'Julho'
		],
		[
			'label' => 'Agosto',
		],
		[
			'label' => 'Setembro',
		],
		[
			'label' => 'Outubro',
		],
		[
			'label' => 'Novembro',
		],
		[
			'label' => 'Dezembro',
		]
	];

	public $month = [];
	public $year = 2023;

	#[Url]
	public $month_ref = '';

	public $selectedMonth = -1;

	public function increment()
	{
		$this->selectedMonth = $this->selectedMonth < 11 ? $this->selectedMonth + 1 : 0;

		if ($this->selectedMonth === 0) {
			$this->year += 1;
		}

		$this->month = $this->months[$this->selectedMonth];

		$this->updateMonthRefQueryString();

		$this->dispatch('update-month', month: $this->selectedMonth, year: $this->year);
	}

	public function decrement()
	{
		$this->selectedMonth = $this->selectedMonth > 0 ? $this->selectedMonth - 1 : 11;

		if ($this->selectedMonth === 11) {
			$this->year -= 1;
		}

		$this->month = $this->months[$this->selectedMonth];

		$this->updateMonthRefQueryString();

		$this->dispatch('update-month', month: $this->selectedMonth, year: $this->year);
	}

	public function updateMonthRefQueryString()
	{
		$month = $this->selectedMonth + 1;
		$month = $month < 10 ? '0' . $month : $month;
		$year = str_replace('20', '', $this->year);

		$this->month_ref = "{$month}_{$year}";
	}

	public function mount()
	{
		if (!$this->month_ref) {
			$this->year = (int) date('Y');
			$this->selectedMonth = (int) date('m') - 1;
		} else {
			$split = explode("_", $this->month_ref);
			if (isset($split[0]) && isset($split[1])) {
				$this->year = (int) '20' . $split[1];
				$this->selectedMonth = (int) $split[0] - 1;
			}
		}

		$this->month = $this->months[$this->selectedMonth];
	}

	public function render()
	{
		return view('livewire.months-scroll');
	}
}
