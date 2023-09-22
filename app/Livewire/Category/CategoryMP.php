<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Livewire\Component;

class CategoryMP extends Component
{
    public $deleteItem = [];

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
            // [
            //     ['value' => 1, 'disabled' => true],
            //     ['value' => 4903],
            //     ['value' => 'Normal'],
            //     ['value' => 'Limpeza'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '644.77', 'name' => 'value'],
            //     ['value' => '']
            // ],
            // [
            //     ['value' => 2, 'disabled' => true],
            //     ['value' => 5007],
            //     ['value' => 'Extra'],
            //     ['value' => 'Estocáveis CD'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '1400.59', 'name' => 'value'],
            //     ['value' => 'Mercadoria Danificada ']
            // ],
        ],

        'new' => [
            ['value' => '', 'disabled' => true],
            ['value' => '', 'type' => 'text'],
            ['value' => '', 'type' => 'text'],
            ['value' => '', 'type' => 'text'],
            ['value' => '', 'type' => 'date'],
            ['value' => '0,00', 'name' => 'value', 'type' => 'number'],
            ['value' => '', 'type' => 'text']
        ]
    ];

    public function deleteRowItem($rowIndex)
    {
        $this->deleteItem[$rowIndex] = true;
    }

    public function cancelDeleteItem($rowIndex)
    {
        $this->deleteItem[$rowIndex] = false;
    }

    public function confirmDeleteItem($rowIndex)
    {
        unset($this->mp['rows'][$rowIndex]);
        unset($this->deleteItem[$rowIndex]);

        return true;
    }

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->mp['rows'][$rowIndex][$columnIndex]['value'] = $value;
    }

    public function increment()
    {
        $newItem = $this->mp['new'];
        $newItem[0]['value'] = sizeof($this->mp['rows']) + 1;
        $newItem[4]['value'] = date('Y-m-d');

        array_push($this->mp['rows'], $newItem);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->mp['rows'] as $row) {
            foreach ($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'value') {
                    $total += (float) $arr['value'];
                }
            }
        }

        return $total;
    }

    public function save()
    {
        if (sizeof($this->mp['rows']) == 0) {
            return;
        }

        $weekref = session('preview')['week_ref'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = number_format($this->getTotal(), 2);
        $preview->events = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->mp);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'week_ref' => $weekref,
                'option_name' => 'mp',
            ],
            [
                'week_ref' => $weekref,
                'option_name' => 'mp',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        return $this->redirect('/categoria?filter=mp', navigate: true);
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'];

        $mp = Option::where('week_ref', $weekref)
            ->where('option_name', 'mp')
            ->first();

        if ($mp) {
            $this->mp = unserialize($mp->option_value);
        }
    }

    public function render()
    {
        return view('livewire.category.category-m-p');
    }
}
