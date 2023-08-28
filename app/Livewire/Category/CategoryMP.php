<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryMP extends Component
{
    public $mp = [
        'labels' => [
            'Item',
            'Número Pedido',
            'Tipo Pedido',
            'Grupo de Compras',
            'Data',
            'Valor',
            'Observação',

        ],

        'rows' => [
            [
                ['value' => 1, 'disabled' => true],
                ['value' => 4903],
                ['value' => 'Normal'],
                ['value' => 'Limpeza'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '644.77', 'name' => 'value'],
                ['value' => '']
            ],
            [
                ['value' => 2, 'disabled' => true],
                ['value' => 5007],
                ['value' => 'Extra'],
                ['value' => 'Estocáveis CD'],
                ['value' => '2023-08-01', 'type' => 'date'],
                ['value' => '1400.59', 'name' => 'value'],
                ['value' => 'Mercadoria Danificada ']
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
            label: "mp",
            value: $total
        );

        return view('livewire.category.category-m-p');
    }
}
