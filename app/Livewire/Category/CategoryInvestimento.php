<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryInvestimento extends Component
{
    public $mp = [
        'labels' => [
            'Item',
            'Conta',
            'Data',
            'Valor',
            'Observação',

        ],

        'rows' => [
            [
                ['value' => 1, 'disabled' => true],
                ['value' => 'Revitalização'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '644.77', 'name' => 'value'],
                ['value' => '']
            ],
            [
                ['value' => 2, 'disabled' => true],
                ['value' => 'Compra Forno'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '189.90', 'name' => 'value'],
                ['value' => '']
            ],
        ]

    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->mp['rows'][$rowIndex][$columnIndex]['value'] = $value;
    }

    public function render()
    {
        $total = 50;

        $this->dispatch(
            'update-bar-total',
            label: "investimento",
            value: $total
        );

        return view('livewire.category.category-investimento');
    }
}
