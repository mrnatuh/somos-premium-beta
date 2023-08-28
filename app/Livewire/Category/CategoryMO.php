<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryMO extends Component
{

    public $parameters = [
        'labels' => [
            [
                'label' => '<span>Dias</span><span>Seg a Sáb</span>'
            ],
            [
                'label' => '<span>Domingos</span><span>Feriados</span>'
            ],
            [
                'label' => '<span>Cesta</span><span>Básica</span>'
            ],
            [
                'label' => '<span>Assitência</span><span>Médica</span><span>Titular</span>'
            ],
            [
                'label' => '<span>Assitência</span><span>Médica</span><span>Dependentes</span>'
            ],
            [
                'label' => '<span>Exames</span>'
            ],
            [
                'label' => '<span>Assistência</span><span>Odontológica</span>'
            ],
            [
                'label' => '<span>Contribuição</span><span>Sindical</span>'
            ],
            [
                'label' => '<span>INSS</span>'
            ],
            [
                'label' => '<span>FGTS</span>'
            ],
            [
                'label' => '<span>Provisão</span><span>Férias</span>'
            ],
            [
                'label' => '<span>Provisão</span><span>13º</span>'
            ],
            [
                'label' => '<span>Taxa</span><span>Vale Transporte</span>'
            ],
        ],

        "rows" => [
            [
                [
                    'label' => '26',
                    'value' => 26,
                    'name' => 'dias_uteis',
                    'type' => 'number'
                ],
                [
                    'label' => '5',
                    'value' => 5,
                    'name' => 'feriados',
                    'type' => 'number'
                ],
                [
                    'label' => '190,00',
                    'value' => 190,
                    'name' => 'cesta_basica',
                    'type' => 'number'
                ],
                [
                    'label' => '430,00',
                    'value' => 430,
                    'name' => 'assistencia_medica_titular',
                    'type' => 'number'
                ],
                [
                    'label' => '150,00',
                    'value' => 150,
                    'name' => 'assistencia_medica_dependentes',
                    'type' => 'number'
                ],
                [
                    'label' => '22,50',
                    'value' => 22.5,
                    'name' => 'exames',
                    'type' => 'number'
                ],
                [
                    'label' => '12,45',
                    'value' => 12.45,
                    'name' => 'assistencia_odontologica',
                    'type' => 'number'
                ],
                [
                    'label' => '50',
                    'value' => 50,
                    'name' => 'contribuicao_sindical',
                    'type' => 'number'
                ],
                [
                    'label' => '28,80%',
                    'value' => 28.8,
                    'name' => 'inss',
                    'type' => 'percent'
                ],
                [
                    'label' => '8%',
                    'value' => 8,
                    'name' => 'fgts',
                    'type' => 'percent'
                ],
                [
                    'label' => '15,10%',
                    'value' => 15.1,
                    'name' => 'provisao_ferias',
                    'type' => 'percent'
                ],
                [
                    'label' => '11,40%',
                    'value' => 11.4,
                    'name' => 'provisao_decimo_terceiro',
                    'type' => 'percent'
                ],
                [
                    'label' => '0',
                    'value' => 0,
                    'name' => 'taxa_vale_transporte',
                    'type' => 'number'
                ]
            ]
        ]
    ];

    public $mo = [
        'labels' => [
            [
                'label' => '<span>Nome</span>',
            ],
            [
                'label' => '<span>Salário</span>',
            ],
            [
                'label' => '<span>Dias</span><span>Trabalhados</span>',
            ],
            [
                'label' => '<span>Situação</span>',
            ],
            [
                'label' => '<span>Cesta</span><span>Básica   </span>',
            ],
            [
                'label' => '<span>Assistência</span><span>Médica</span>',
            ],
            [
                'label' => '<span>Número</span><span>Dependentes</span>',
            ],
            [
                'label' => '<span>Assistência</span><span>Odontológica</span>',
            ],
            [
                'label' => '<span>Contribuição</span><span>Sindical</span>',
            ],
            [
                'label' => '<span>Vale</span><span>Transporte</span>',
            ],
            [
                'label' => '<span>Vale</span><span>Transporte</span><span>Valor Diário</span>',
            ],
            [
                'label' => '<span>Salário Bruto</span>',
            ],
            [
                'label' => '<span>Total</span><span>Funcionário</span>',
            ],
            [
                'label' => '<span>DSR</span>',
            ],
        ]
    ];

    public function render()
    {
        return view('livewire.category.category-m-o');
    }
}
