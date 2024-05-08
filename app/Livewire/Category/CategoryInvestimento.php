<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CategoryInvestimento extends Component
{
    public $deleteItem = [];

    public $edit = true;
    public $wait = 0;

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
            ['value' => '', 'type' => 'number', 'name' => 'value'],
            ['value' => '', 'type' => 'text', 'name' => 'description']
        ]
    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        if (isset($this->investimento['rows'][$rowIndex][$columnIndex]['name']) && $this->investimento['rows'][$rowIndex][$columnIndex]['name'] == 'value') {

            $value = $value ?? 0;

            $value = (float) str_replace(',', '.', $value);
            $this->investimento['rows'][$rowIndex][$columnIndex]['set'] = $value;

            $this->investimento['rows'][$rowIndex][$columnIndex]['value'] = $value;
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

        $this->save();
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->investimento['rows'] as $row) {
            foreach ($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'value') {
                    if (isset($arr['set'])) {
                        $total += $arr['set'] ?? 0;
                    }
                }
            }
        }

        return $total;
    }

    public function save()
    {
        if (!$this->edit) return;

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
        ]);
    }

    public function render()
    {
        $is_page_realizadas = (int) session('preview')['realizadas'];

        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'] ?? false;

        if ($cc) {
            $filename = "/previews/{$cc}_{$weekref}_rou.json";
            $data_exists = Storage::exists($filename);

            if ($data_exists && !$is_page_realizadas) {
                $data = Storage::get($filename);
                $investimento = json_decode($data, true);
                $this->investimento = $investimento['option_value'];
                $this->edit = false;
            } else {
                $investimento = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'rou']])->first();

                if ($investimento) {
                    $this->investimento = unserialize($investimento->option_value);
                }

                if ($is_page_realizadas) {
                    $this->edit = false;
                }
            }
        }

        if ($this->wait) {
            $this->edit = false;
        }

        return view('livewire.category.category-investimento');
    }
}
