<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Livewire\Component;

class CategoryGD extends Component
{
    public $deleteItem = [];

    public $gd = [
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
            //     ['value' => 'Material Esportivo'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '644.77', 'name' => 'value'],
            //     ['value' => 'Compra material de escritório']
            // ],
            // [
            //     ['value' => 2, 'disabled' => true],
            //     ['value' => 'Impressora'],
            //     ['value' => '2023-08-01', 'type' => 'date'],
            //     ['value' => '189.90', 'name' => 'value'],
            //     ['value' => 'Locação impressora']
            // ],
        ],

        'new' => [
            ['value' => '', 'disabled' => true],
            ['value' => '', 'type' => 'text', 'name' => 'account'],
            ['value' => '', 'type' =>'date', 'name' => 'date'],
            ['value' => '', 'type' => 'text', 'name' => 'value'],
            ['value' =>'', 'type' => 'text', 'name' => 'description']
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
        unset($this->gd['rows'][$rowIndex]);
        unset($this->deleteItem[$rowIndex]);

        $this->save();
    }

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->gd['rows'][$rowIndex][$columnIndex]['value'] = $value;

        $this->save();
    }

    public function increment()
    {
        $newItem = $this->gd['new'];
        $newItem[0]['value'] = sizeof($this->gd['rows']) + 1;
        $newItem[2]['value'] = date('Y-m-d');

        array_push($this->gd['rows'], $newItem);

        $this->save();
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->gd['rows'] as $row) {
            foreach ($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'value') {
                    $total += $arr['value'] ?? 0;
                }
            }
        }

        return $total;
    }


    public function save()
    {
        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'];

        // acha o preview
        $preview = Preview::where([['cc', '=', $cc], ['week_ref', '=', $weekref]])->first();

        // calcula o total
        $total = $this->getTotal();
        $preview->gd = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->gd);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'gd',
            ],
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'gd',
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
        ]);
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'] ?? false;

        if ($cc) {
            $gd = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'gd']])->first();

            if ($gd) {
                $this->gd = unserialize($gd->option_value);
            }
        }
    }


    public function render()
    {
        return view('livewire.category.category-g-d');
    }
}
