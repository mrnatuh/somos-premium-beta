<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Livewire\Component;

class CategoryInvestimento extends Component
{
    public $deleteItem = [];

    public $investimento = [
        'labels' => [
            'Item',
            'Conta',
            'Data',
            'Valor',
            'Observação',

        ],

        'rows' => [
            // [
            //     ['value' => 1, 'disabled' => true],
            //     ['value' => 'Revitalização'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '644.77', 'name' => 'value'],
            //     ['value' => '']
            // ],
            // [
            //     ['value' => 2, 'disabled' => true],
            //     ['value' => 'Compra Forno'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '189.90', 'name' => 'value'],
            //     ['value' => '']
            // ],
        ],

        'new' => [
            ['value' => '', 'disabled' => true],
            ['value' => '', 'type' => 'text', 'name' => 'account'],
            ['value' => '', 'type' => 'date', 'name' => 'date'],
            ['value' => '0', 'type' => 'number', 'name' => 'value'],
            ['value' => '', 'type' => 'text', 'name' => 'description']
        ]
    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        if (isset($this->investimento['rows'][$rowIndex][$columnIndex]['name']) && $this->investimento['rows'][$rowIndex][$columnIndex]['name'] == 'value') {
            $value = (float) str_replace(',', '.', $value);

            $this->investimento['rows'][$rowIndex][$columnIndex]['set'] = number_format($value, 2);
            $this->investimento['rows'][$rowIndex][$columnIndex]['value'] = 'R$ ' . number_format($value, 2, ',', '.');
        } else {
            $this->investimento['rows'][$rowIndex][$columnIndex]['value'] = $value;
        }

        $this->save();
    }

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
        unset($this->investimento['rows'][$rowIndex]);
        unset($this->deleteItem[$rowIndex]);

        $this->save();
    }

    public function increment()
    {
        $newItem = $this->investimento['new'];
        $newItem[0]['value'] = sizeof($this->investimento['rows']) + 1;
        $newItem[2]['value'] = date('Y-m-d');

        array_push($this->investimento['rows'], $newItem);
    }

    public function getTotal()
    {
        $total = 0;

        dd($this->investimento['rows']);

        foreach ($this->investimento['rows'] as $row) {
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
        if (sizeof($this->investimento['rows']) == 0) {
            return;
        }

        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = $this->getTotal();
        $preview->rou = $total;

        dd($total);

        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->investimento);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'rou',
            ],
            [
                'option_value' => $content,
                'total' => $total,
            ]
        );

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        $this->dispatch('update-bar-total', [
            'cc' => $cc,
            'weekref' => $weekref
        ]);;
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'] ?? false;

        if ($cc) {
            $investimento = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'rou']])->first();

            if ($investimento) {
                $this->investimento = unserialize($investimento->option_value);
            }
        }
    }


    public function render()
    {
        return view('livewire.category.category-investimento');
    }
}
