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
        ],

        "rows" => [
            [
                ['label' => 'Oliveira Silva', 'value' => 'Oliveira Silva', 'name' => 'name'],
                ['label' => '1.200,00', 'value' => 1200, 'name' => 'salario'],
                ['label' => '30', 'value' => 30, 'name' => 'dias_trabalhados'],
                ['label' => 'Ativo', 'value' => 1, 'name' => 'situacao'],
                ['label' => 'Sim', 'value' => 1, 'name' => 'cesta_basica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
                ['label' => '1', 'value' => 1, 'name' => 'numero_dependentes'],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'vale_transporte', "type" => "select"],
                ['label' => '6,90', 'value' => 6.9, 'name' => 'valor_transporte_valor_diario'],
                ['label' => '1.200,00', 'value' => 1200, 'name' => 'salario_bruto'],
                ['label' => '1.400,00', 'value' => 1400, 'name' => 'total_funcionario'],
                ['label' => '0', 'value' => 0, 'name' => 'dsr'],
            ],
            [
                ['label' => 'Kratos', 'value' => 'Kratos', 'name' => 'name'],
                ['label' => '1.000,00', 'value' => 1200, 'name' => 'salario'],
                ['label' => '30', 'value' => 30, 'name' => 'dias_trabalhados'],
                ['label' => 'Ativo', 'value' => 1, 'name' => 'situacao'],
                ['label' => 'Sim', 'value' => 1, 'name' => 'cesta_basica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
                ['label' => '0', 'value' => 0, 'name' => 'numero_dependentes'],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'vale_transporte', "type" => "select"],
                ['label' => '6,90', 'value' => 6.9, 'name' => 'valor_transporte_valor_diario'],
                ['label' => '1.000,00', 'value' => 1200, 'name' => 'salario_bruto'],
                ['label' => '1.200,00', 'value' => 1400, 'name' => 'total_funcionario'],
                ['label' => '0',  'value' => 0, 'name' => 'dsr'],
            ],
            [
                ['label' => 'Batman', 'value' => 'Kratos', 'name' => 'name'],
                ['label' => '1.400,00', 'value' => 1200, 'name' => 'salario'],
                ['label' => '10', 'value' => 30, 'name' => 'dias_trabalhados'],
                ['label' => 'Afastado', 'value' => 2, 'name' => 'situacao'],
                ['label' => 'Não', 'value' => 0, 'name' => 'cesta_basica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
                ['label' => '3', 'value' => 3, 'name' => 'numero_dependentes'],
                ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
                ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
                ['label' => 'Não', 'value' => 0, 'name' => 'vale_transporte', "type" => "select"],
                ['label' => '0', 'value' => 0, 'name' => 'valor_transporte_valor_diario'],
                ['label' => '466,67', 'value' => 466.67, 'name' => 'salario_bruto'],
                ['label' => '1.800,00', 'value' => 1400, 'name' => 'total_funcionario'],
                ['label' => '0',  'value' => 0, 'name' => 'dsr'],
            ],
            [
                ['label' => 'Total', 'value' => 'Total', 'name' => 'total_name'],
                ['label' => '3.600,00', 'value' => 3600, 'name' => 'total_salario'],
                ['label' => '', 'value' => 0, 'name' => 'total_dias_trabalhados'],
                ['label' => '', 'value' => 0, 'name' => 'total_situacao'],
                ['label' => '', 'value' => 0, 'name' => 'total_cesta_basica'],
                ['label' => '', 'value' => 0, 'name' => 'total_assistencia_medica'],
                ['label' => '', 'value' => 0, 'name' => 'total_numero_dependentes'],
                ['label' => '', 'value' => 0, 'name' => 'total_assistencia_odontologica', "type" => "select"],
                ['label' => '', 'value' => 0, 'name' => 'total_contribuicao_sindical', "type" => "select"],
                ['label' => '', 'value' => 0, 'name' => 'total_vale_transporte', "type" => "select"],
                ['label' => '', 'value' => 0, 'name' => 'total_valor_transporte_valor_diario'],
                ['label' => '2.666,67', 'value' => 2666.67, 'name' => 'total_salario_bruto'],
                ['label' => '5.400,00', 'value' => 5400, 'name' => 'total_total_funcionario'],
                ['label' => '',  'value' => 0, 'name' => 'total_dsr'],
            ]
        ]
    ];

    public function render()
    {
        return view('livewire.category.category-m-o');
    }
}
