<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class MonthsScroll extends Component
{
	public $list_of_months = [
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

	#[Url]
	public $month_ref = '';

	public $month = '';

	public $year = 2023;

	public function increment()
	{
		$dt = Carbon::parse(str_replace("_", "/", $this->month_ref));
		$this->month_ref = $dt->addMonths(1)->format('m\_y');
		$this->dispatch('month-scroll-updated', month_ref: $this->month_ref);
	}

	public function decrement()
	{
		$dt = Carbon::parse(str_replace("_", "/", $this->month_ref));
		$this->month_ref = $dt->subMonths(1)->format('m\_y');
		$this->dispatch('month-scroll-updated', month_ref: $this->month_ref);
	}

	public function render()
	{
		if ($this->month_ref) {
			$dt = Carbon::parse(str_replace("_", "/", $this->month_ref));
			$year = $dt->format('Y');
			$month = (int) $dt->format('m');
			$this->year = $year;
			$this->month_ref = $dt->format('m\_y');
			$this->month = $this->list_of_months[$month - 1];
		} else {
			$year = date('Y');
			$month = (int) date('m');
			$this->year = $year;
			$this->month_ref = date('m') . '_' . substr(date('Y'), -2);
			$this->month = $this->list_of_months[$month - 1];
		}

		return view('livewire.months-scroll', [
			'month' => $this->month,
			'year' => $this->year,
		]);
	}
}
