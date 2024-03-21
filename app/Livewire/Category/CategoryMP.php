<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CategoryMP extends Component
{
    public $edit = true;

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
            ['value' => '', 'type' => 'text', 'name' => 'order'],
            ['value' => '', 'type' => 'text', 'name' => 'type'],
            ['value' => '', 'type' => 'text', 'name' => 'gc'],
            ['value' => '', 'type' => 'date', 'name' => 'date'],
            ['value' => '0', 'name' => 'value', 'type' => 'number'],
            ['value' => '', 'type' => 'text', 'name' => 'description']
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

        $this->save();
    }

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->mp['rows'][$rowIndex][$columnIndex]['value'] = $value;

        $this->save();
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
        if (!$this->edit) return;

        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = $this->getTotal();
        $preview->mp = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->mp);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'mp',
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
        ]);

        // return $this->redirect('/categoria?filter=mp', navigate: true);
    }

    public function mount()
    {
        $is_page_realizadas = (int) session('preview')['realizadas'];

        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'] ?? false;

        if ($cc) {
            $filename = "/previews/{$cc}_{$weekref}_mp.json";
            $data_exists = Storage::exists($filename);

            if ($data_exists && !$is_page_realizadas) {
                $data = Storage::get($filename);
                $eventos = json_decode($data, true);
                $this->mp = $eventos['option_value'];
                $this->edit = false;
            } else {
                $mp = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'mp']])->first();


                if ($mp) {
                    $this->mp = unserialize($mp->option_value);
                }

                if ($is_page_realizadas) {
                    $this->edit = false;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.category.category-m-p');
    }
}
