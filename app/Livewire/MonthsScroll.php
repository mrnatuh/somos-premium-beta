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
		$str_date = explode("_", $this->month_ref);
		$str_date = $str_date[1] . "-" . $str_date[0] . "-01";
		$dt = Carbon::parse($str_date);
		$add = $dt->addMonths(1);
		$this->month_ref = $add->format('m\_y');
		$this->dispatch('month-scroll-updated', month_ref: $this->month_ref);
	}

	public function decrement()
	{
		$str_date = explode("_", $this->month_ref);
		$str_date = $str_date[1] . "-" . $str_date[0] . "-01";
		$dt = Carbon::parse($str_date);
		$sub = $dt->subMonths(1);
		$this->month_ref = $sub->format('m\_y');
		$this->year = $sub->format('Y');
		$this->dispatch('month-scroll-updated', month_ref: $this->month_ref);
	}

	public function render()
	{
		if ($this->month_ref) {
			$str_date = explode("_", $this->month_ref);
			$str_date = $str_date[1] . "-" . $str_date[0] . "-01";
			$dt = Carbon::parse($str_date);
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
