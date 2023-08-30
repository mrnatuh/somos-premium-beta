<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryGD extends Component
{
    public $gd = [
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
        ],

        'new' => [
            ['value' => '', 'disabled' => true],
            ['value' => '', 'type' => 'text'],
            ['value' => '', 'type' => 'date'],
            ['value' => '', 'type' => 'text', 'name' => 'value'],
            ['value' => '']
        ]

    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->gd['rows'][$rowIndex][$columnIndex]['value'] = $value;
    }

    public function increment()
    {
        $newItem = $this->gd['new'];
        $newItem[0]['value'] = sizeof($this->gd['rows']) + 1;
        $newItem[2]['value'] = date('Y-m-d');

        array_push($this->gd['rows'], $newItem);
    }


    public function render()
    {
        $total = 0;

        foreach ($this->gd['rows'] as $row) {
            foreach ($row as $key => $arr) {
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
