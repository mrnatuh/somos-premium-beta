<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
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

    public function getTotal()
    {
        $total = 0;

        foreach ($this->gd['rows'] as $row) {
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
        if (sizeof($this->gd['rows']) == 0) {
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
        $content = serialize($this->gd);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'week_ref' => $weekref,
                'option_name' => 'gd',
            ],
            [
                'week_ref' => $weekref,
                'option_name' => 'gd',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        // session()->forget('message');

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        return true;
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'];

        $gd = Option::where('week_ref', $weekref)
            ->where('option_name', 'gd')
            ->first();

        if ($gd) {
            $this->gd = unserialize($gd->option_value);
        }
    }


    public function render()
    {
        return view('livewire.category.category-g-d');
    }
}
