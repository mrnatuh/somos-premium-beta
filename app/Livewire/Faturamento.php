<?php

namespace App\Livewire;

use Livewire\Component;

class Faturamento extends Component
{
    public $companies = [
        [
            "title" => "Mercado Livre",

            "colspan" => 2,
            "rowspan" => 8,

            "labels" => ['Almoço', 'Jantar'],

            "prices" => [
                ["value" => 17.25],
                ["value" => 14.25],
            ],

            "rows" => [
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
                [
                    ["value" => 30],
                    ["value" => 15]
                ],
                [
                    ["value" => 0],
                    ["value" => 0]
                ],
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
                [
                    ["value" => 100],
                    ["value" => 80]
                ],
            ],
        ],

        // [
        //     "title" => "Graber",

        //     "colspan" => 2,
        //     "rowspan" => 7,

        //     "labels" => ['Almoço', 'Jantar'],

        //     "prices" => [22.9, 29.20],

        //     "rows" => [
        //         [30, 50],
        //         [30, 50],
        //         [30, 50],
        //         [30, 50],
        //         [30, 0],
        //         [30, 0],
        //         [30, 50],
        //         [100, 50]
        //     ],
        // ],

        // [
        //     "title" => "B2 Blue",

        //     "colspan" => 3,
        //     "rowspan" => 7,

        //     "labels" => ['Ceia', 'Almoço', 'Jantar'],

        //     "prices" => [7.9, 18.99, 22.30],

        //     "rows" => [
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200],
        //         [200, 200, 200]
        //     ],
        // ],
    ];

    public function updatePrice($companyIndex, $priceIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            if (isset($this->companies[$companyIndex]['prices'])) {
                $this->companies[$companyIndex]['prices'][$priceIndex]['value'] = (float) $value;
            }
        }
    }

    public function updateQty($companyIndex, $rowIndex, $qtyIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            if (isset($this->companies[$companyIndex]['rows'])) {
                $this->companies[$companyIndex]['rows'][$rowIndex][$qtyIndex]['value'] = (int) $value;
            }
        }
    }

    public function save()
    {
        dd($this->companies);
    }

    public function render()
    {
        $total = 0;

        foreach($this->companies as $company) {
            $prices = $company['prices'];
            $rows = $company['rows'];
            $colspan = $company['colspan'];
            $rowspan = $company['rowspan'];

            for ($c = 0; $c < $colspan; $c++) {
                $price = $prices[$c]['value'];
                for($r = 0; $r < $rowspan; $r++) {
                    $qty = $rows[$r][$c]['value'];
                    $total += $price * $qty;
                }
            }
        }

        $this->dispatch('update-bar-total',
            label: "faturamento",
            value: $total
        );

        return view('livewire.faturamento');
    }
}
