<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryGD extends Component
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
                ['value' => 'Material Esportivo'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '644.77', 'name' => 'value'],
                ['value' => 'Compra material de escritório']
            ],
            [
                ['value' => 2, 'disabled' => true],
                ['value' => 'Impressora'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '189.90', 'name' => 'value'],
                ['value' => 'Locação impressora']
            ],
        ]

    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->mp['rows'][$rowIndex][$columnIndex]['value'] = $value;
    }

    public function render()
    {
        $total = 0;

        foreach ($this->mp['rows'] as $row) {
            foreach($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'value') {
                    $total += (float) $arr['value'];
                }
            }
        }

        $this->dispatch(
            'update-bar-total',
            label: "gd",
            value: $total
        );

        return view('livewire.category.category-g-d');
    }
}
