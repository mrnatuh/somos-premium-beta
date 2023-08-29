<?php

namespace App\Livewire;

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

    public $selectedMonth = 1;

    public function increment()
    {
        $this->selectedMonth = $this->selectedMonth < 11 ? $this->selectedMonth + 1 : 11;
        $this->month = $this->months[$this->selectedMonth];
        $this->dispatch('update-month');
    }

    public function decrement()
    {
        $this->selectedMonth = $this->selectedMonth > 0 ? $this->selectedMonth - 1 : 0;
        $this->month = $this->months[$this->selectedMonth];
        $this->dispatch('update-month');
    }

    public function mount()
    {
        $this->selectedMonth = 6; //(int) date('m') - 1;
        $this->month = $this->months[$this->selectedMonth];
    }

    public function render()
    {
        return view('livewire.months-scroll');
    }
}
