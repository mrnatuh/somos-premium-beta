<?php

namespace App\Livewire;

use Livewire\Component;

class Faturamento extends Component
{
    public $companies = [
        [
            "title" => "Mercado Livre",

            "colspan" => 2,
            "rowspan" => 7,

            "labels" => ['Almoço', 'Jantar'],

            "prices" => [17.25, 14.25],

            "rows" => [
                [100, 80],
                [100, 80],
                [100, 80],
                [100, 80],
                [30, 15],
                [0, 0],
                [100, 80],
                [100, 80]
            ],
        ],

        [
            "title" => "Graber",

            "colspan" => 2,
            "rowspan" => 7,

            "labels" => ['Almoço', 'Jantar'],

            "prices" => [22.9, 29.20],

            "rows" => [
                [30, 50],
                [30, 50],
                [30, 50],
                [30, 50],
                [30, 0],
                [30, 0],
                [30, 50],
                [100, 50]
            ],
        ],

        [
            "title" => "B2 Blue",

            "colspan" => 3,
            "rowspan" => 7,

            "labels" => ['Ceia', 'Almoço', 'Jantar'],

            "prices" => [7.9, 18.99, 22.30],

            "rows" => [
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200],
                [200, 200, 200]
            ],
        ],
    ];

    public function render()
    {
        return view('livewire.faturamento');
    }
}
