<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryEvent extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $events = [
        'add' => true,

        'labels' => [
            'Cliente',
            'Quantidade',
            'Valor Unitário',
            'Valor Total',
            'Data Evento',
            'Data Faturamento',
            'Descrição'
        ],

        'rows' => [
            [
                ['value' => 'Mercado Livre'],
                ['value' => 100, 'type' => 'number', 'name' => 'qty'],
                ['value' => 120, 'type' => 'number', 'name' => 'value'],
                ['value' => 'R$ 12.000,00', 'disabled' => true, 'name' => 'total'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '2023-09-01', 'type' => 'date'],
                ['value' => 'Festa Junina', 'type' => 'text']
            ],
            [
                ['value' => 'Graber'],
                ['value' => 30, 'type' => 'number', 'name' => 'qty'],
                ['value' => 100, 'type' => 'number', 'name' => 'value'],
                ['value' => 'R$ 3.000,00', 'disabled' => true, 'name' => 'total'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '2023-09-01', 'type' => 'date'],
                ['value' => 'Dia da Independência', 'type' => 'text']
            ]
        ],

        "new" => [
            ['value' => '', 'type' => 'select'],
            ['value' => 0, 'type' => 'number', 'name' => 'qty'],
            ['value' => 0, 'type' => 'number', 'name' => 'value'],
            ['value' => 'R$ 0,00', 'disabled' => true, 'name' => 'total'],
            ['value' => '', 'type' => 'date'],
            ['value' => '', 'type' => 'date'],
            ['value' => '', 'type' => 'text']
        ]
    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->events['rows'][$rowIndex][$columnIndex]['value'] = $value;
        $this->updateRowTotal($rowIndex);
    }

    public function increment()
    {
        array_push($this->events['rows'], $this->events['new']);
    }

    public function updateRowTotal($rowIndex)
    {
        $qty = 0;
        $value = 0;
        $totalIndex = -1;

        foreach ($this->events['rows'][$rowIndex] as $key => $arr) {
            if (isset($arr['name']) && $arr['name'] == 'qty') {
                $qty = $arr['value'];
            }

            if (isset($arr['name']) && $arr['name'] == 'value') {
                $value = $arr['value'];
            }

            if (isset($arr['name']) && $arr['name'] == 'total') {
                $totalIndex = $key;
            }
        }

        $totalRow = $qty * $value;

        $this->events['rows'][$rowIndex][$totalIndex]['value'] = 'R$ ' . number_format($totalRow, 2, ',', '.');
    }

    public function render()
    {
        $total = 0;

        foreach ($this->events['rows'] as $row) {
            $qty = 0;
            $value = 0;

            foreach ($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'qty') {
                    $qty = $arr['value'];
                }

                if (isset($arr['name']) && $arr['name'] == 'value') {
                    $value = $arr['value'];
                }
            }

            $total += $qty * $value;
        }

        $this->dispatch(
            'update-bar-total',
            label: "events",
            value: $total
        );

        return view('livewire.category.category-event');
    }
}
