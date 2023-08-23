<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class DashboardIndex extends Component
{
    public $preview = [];

    public function render()
    {
        $this->preview['labels'] = [
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

        $this->preview['rows'] = [
            [
                ['value' => 070423, 'align' => 'justify-center'],
                ['value' => 'R$ ' . number_format(63500.30, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(3500.30, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(7500, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => 'R$ ' . number_format(1500, 2, ',', '.'), 'arrow' => 'down-green'],
                ['value' => '40%', 'arrow' => 'down-green']
            ]
        ];

        return view('livewire.dashboard.index');
    }
}
