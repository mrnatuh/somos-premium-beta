<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $columns = [
            [
                'label' => 'Prévia',
                'slug' => 'previa',
                'arrow' => 'down-silver',
                'align' => 'justify-center',
            ],
            [
                'label' => 'Faturamento',
                'slug' => 'faturamento',
                'arrow' => 'down-silver',
                'align' => 'justify-center'
            ],
            [
                'label' => 'MP',
                'slug' => 'mp',
                'arrow' => 'down-silver'
            ],
            [
                'label' => 'MO',
                'slug' => 'mo',
                'arrow' => 'down-silver'
            ],
            [
                'label' => 'GD',
                'slug' => 'gd',
                'arrow' => 'down-silver'
            ],
            [
                'label' => 'ROU',
                'slug' => 'rou',
                'arrow' => 'down-silver'
            ]
        ];

        $rows = [
            [
                ['value' => 070423, 'align' => 'justify-center' ],
                ['value' => 'R$ ' . number_format(63500.30, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(3500.30, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(7500, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(1500, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => '40%', 'arrow' => 'down-green']
            ]
        ];

        return view('dashboard', [
            'previas' => [
                'columns' => $columns,
                'rows' => $rows
            ],
        ]);
    }
}
