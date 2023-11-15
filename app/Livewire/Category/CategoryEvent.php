<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryEvent extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $deleteItem = [];

    public $events = [
        'add' => true,

        'labels' => [
            'Cliente',
            'Quantidade',
            'Valor Unitário',
            'Valor Total',
            'Data Evento',
            'Data Faturamento',
            'Descrição'
        ],

        'rows' => []
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
        unset($this->events['rows'][$rowIndex]);
        unset($this->deleteItem[$rowIndex]);

        $this->save();
    }

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->events['rows'][$rowIndex][$columnIndex]['value'] = $value;

        $this->updateRowTotal($rowIndex);

        $this->save();
    }

    public function updateRowTotal($rowIndex)
    {
        $qty = 0;
        $value = 0;
        $totalIndex = -1;

        foreach ($this->events['rows'][$rowIndex] as $key => $arr) {
            if (isset($arr['name']) && $arr['name'] == 'qty') {
                $qty = $arr['value'];
            }

            if (isset($arr['name']) && $arr['name'] == 'value') {
                $value = $arr['value'];
            }

            if (isset($arr['name']) && $arr['name'] == 'total') {
                $totalIndex = $key;
            }
        }

        $totalRow = $qty * $value;

        $this->events['rows'][$rowIndex][$totalIndex]['set'] = $totalRow;
        $this->events['rows'][$rowIndex][$totalIndex]['value'] = 'R$ ' . number_format($totalRow, 2, ',', '.');

        $this->save();
    }

    #[On('search-add-client')]
    public function addClient($client_id)
    {
        $client = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where('A1_COD', $client_id)
            ->first();

        if (!$client) {
            return;
        }

        $add_row = [
            ['name' => 'client', 'value' => trim($client->A1_NOME), 'id' => trim($client->A1_CGC)],
            ['value' => 0, 'type' => 'number', 'name' => 'qty'],
            ['value' => 0, 'type' => 'number', 'name' => 'value'],
            ['value' => 'R$ 0,00', 'disabled' => true, 'name' => 'total', 'set' => 0],
            ['value' => '', 'type' => 'date', 'name' => 'date_event'],
            ['value' => '', 'type' => 'date', 'name' => 'date_invoicing'],
            ['value' => '', 'type' => 'text', 'name' => 'description']
        ];

        array_push($this->events['rows'], $add_row);

        $this->save();
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->events['rows'] as $row) {
            foreach ($row as $key => $arr) {
                if (isset($arr['name']) && $arr['name'] == 'total') {
                    $total += $arr['set'] ?? 0;
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
        $preview->events = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->events);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'eventos',
            ],
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'eventos',
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

        return true;
    }


    public function mount()
    {
        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'] ?? false;

        if ($cc) {
            $eventos = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'eventos']])->first();

            if ($eventos) {
                $this->events = unserialize($eventos->option_value);
            }
        }
    }

    public function render()
    {
        return view('livewire.category.category-event');
    }
}
