<?php

namespace App\Livewire\Category;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryInvoicing extends Component
{
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

    #[On('invoicing-add-client')]
    public function addClient($client_id)
    {
        $this->dispatch('clear-client-search');

        $client = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where('A1_COD', $client_id)
            ->first();

        if (!$client) {
            return;
        }

        $add_client = [
            "title" => $client->A1_NOME,

            "colspan" => 3,
            "rowspan" => $this->lastOfMonth,

            "labels" => ['Ceia', 'Almoço', 'Jantar'],

            "prices" => [['value' => 1], ['value' => 0], ['value' => 0]],

            "rows" => []
        ];

        for ($i = 0; $i < $add_client['rowspan']; $i++) {
            array_push($add_client['rows'], [
                ['value' => 0], ['value' => 0], ['value' => 0]
            ]);
        }

        array_push($this->companies, $add_client);
    }

    public function save()
    {
    }

    public function render()
    {
        $this->lastOfMonth = (int) Carbon::now()->lastOfMonth()->format('d');

        $total = 0;

        foreach ($this->companies as $company) {
            $prices = $company['prices'] ?? [];
            $rows = $company['rows'] ?? [];
            $colspan = $company['colspan'];
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

        $this->dispatch(
            'update-bar-total',
            label: "faturamento",
            value: $total
        );

        return view('livewire.category.category-invoicing');
    }
}
