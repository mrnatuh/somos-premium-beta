<?php

namespace App\Livewire\Category;

use App\Models\Invoicing;
use App\Models\Option;
use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryInvoicing extends Component
{
    public $deleteItem = [];

    // public $companies = [
    //     [
    //         "title" => "Mercado Livre",

    //         "colspan" => 2,
    //         "rowspan" => 8,

    //         "labels" => ['Almoço', 'Jantar'],

    //         "prices" => [
    //             ["value" => 17.25],
    //             ["value" => 14.25],
    //         ],

    //         "rows" => [
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //             [
    //                 ["value" => 30],
    //                 ["value" => 15]
    //             ],
    //             [
    //                 ["value" => 0],
    //                 ["value" => 0]
    //             ],
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //             [
    //                 ["value" => 100],
    //                 ["value" => 80]
    //             ],
    //         ],
    //     ],

    //     [
    //         "title" => "Graber",

    //         "colspan" => 2,
    //         "rowspan" => 8,

    //         "labels" => ['Almoço', 'Jantar'],

    //         "prices" => [["value" => 22.9], ["value" => 29.20]],

    //         "rows" => [
    //             [["value" => 30], ["value" => 50]],
    //             [["value" => 30], ["value" => 50]],
    //             [["value" => 30], ["value" => 50]],
    //             [["value" => 30], ["value" => 50]],
    //             [["value" => 30], ["value" => 0]],
    //             [["value" => 30], ["value" => 0]],
    //             [["value" => 30], ["value" => 50]],
    //             [["value" => 100], ["value" => 50]]
    //         ],
    //     ],

    //     [
    //         "title" => "B2 Blue",

    //         "colspan" => 3,
    //         "rowspan" => 7,

    //         "labels" => ['Ceia', 'Almoço', 'Jantar'],

    //         "prices" => [['value' => 7.9], ['value' => 18.99], ['value' => 22.30]],

    //         "rows" => [
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //             [['value' => 200], ['value' => 200], ['value' => 200]],
    //         ],
    //     ],
    // ];

    public $lastOfMonth = 0;

    public $companies = [];

    public function updatePrice($companyIndex, $priceIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            if (isset($this->companies[$companyIndex]['prices'])) {
                $this->companies[$companyIndex]['prices'][$priceIndex]['value'] = (float) $value ?? 0;
            }
        }
    }

    public function updateQty($companyIndex, $rowIndex, $qtyIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            if (isset($this->companies[$companyIndex]['rows'])) {
                $this->companies[$companyIndex]['rows'][$rowIndex][$qtyIndex]['value'] = (int) $value ?? 0;
            }
        }
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

        $exists = false;


        foreach ($this->companies as $company) {
            if ($company['id'] === $client->A1_CGC) {
                $exists = true;
            }
        }

        if ($exists) {
            session()->flash('message', [
                'type' => 'warning',
                'message' => 'Cliente já adicionado.'
            ]);

            return;
        }

        $add_client = [
            "id" => trim($client->A1_CGC),

            "title" => trim($client->A1_NOME),

            "colspan" => 2,
            "rowspan" => $this->lastOfMonth,

            "labels" => ['Almoço', 'Jantar'],

            "prices" => [['value' => 1, 'edit' => true], ['value' => 1, 'edit' => true]],

            "rows" => []
        ];

        for ($i = 0; $i < $add_client['rowspan']; $i++) {
            $cols = [];

            for ($c = 0; $c < $add_client['colspan']; $c++) {
                array_push($cols, [
                    'value' => 0,
                ]);
            }

            array_push($add_client['rows'], $cols);
        }

        array_push($this->companies, $add_client);
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->companies as $company) {
            $prices = $company['prices'] ?? [];
            $rows = $company['rows'] ?? [];
            $colspan = sizeof($prices);
            $rowspan = $company['rowspan'];

            if (sizeof($prices)) {
                for ($c = 0; $c < $colspan; $c++) {
                    $price = $prices[$c]['value'];
                    for ($r = 0; $r < $rowspan; $r++) {
                        $qty = $rows[$r][$c]['value'];
                        $total += $price * $qty;
                    }
                }
            }
        }

        return $total;
    }

    public function deleteRowItem($id)
    {
        $this->deleteItem[$id] = true;
    }

    public function cancelDeleteItem($id)
    {
        $this->deleteItem[$id] = false;
    }

    public function confirmDeleteItem($id)
    {
        $rowIndex = -1;

        foreach ($this->companies as $key => $company) {
            if ($company['id'] === $id) {
                $rowIndex = $key;
            }
        }

        if ($rowIndex > -1) {
            unset($this->companies[$rowIndex]);
        }

        return true;
    }

    public function save()
    {
        $weekref = session('preview')['week_ref'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = number_format($this->getTotal(), 2);
        $preview->invoicing = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->companies);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'week_ref' => $weekref,
                'option_name' => 'faturamento',
            ],
            [
                'week_ref' => $weekref,
                'option_name' => 'faturamento',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        return $this->redirect('/categoria?filter=faturamento', navigate: true);
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'];

        $faturamento = Option::where('week_ref', $weekref)
            ->where('option_name', 'faturamento')
            ->first();

        if ($faturamento) {
            $this->companies = unserialize($faturamento->option_value);
        }
    }

    public function render()
    {
        $this->lastOfMonth = (int) Carbon::now()->lastOfMonth()->format('d');

        return view('livewire.category.category-invoicing');
    }
}
