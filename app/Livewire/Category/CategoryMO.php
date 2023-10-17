<?php

namespace App\Livewire\Category;

use App\Models\Parameter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoryMO extends Component
{

    public $parameter = null;

    public $parameters = [];

    public $parameters_options = [];

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

        // "rows" => [
        //     [
        //         ['label' => 'Oliveira Silva', 'value' => 'Oliveira Silva', 'name' => 'name'],
        //         ['label' => '1.200,00', 'value' => 1200, 'name' => 'salario'],
        //         ['label' => '30', 'value' => 30, 'name' => 'dias_trabalhados'],
        //         ['label' => 'Ativo', 'value' => 1, 'name' => 'situacao'],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'cesta_basica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
        //         ['label' => '1', 'value' => 1, 'name' => 'numero_dependentes'],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'vale_transporte', "type" => "select"],
        //         ['label' => '6,90', 'value' => 6.9, 'name' => 'valor_transporte_valor_diario'],
        //         ['label' => '1.200,00', 'value' => 1200, 'name' => 'salario_bruto'],
        //         ['label' => '1.400,00', 'value' => 1400, 'name' => 'total_funcionario'],
        //         ['label' => '0', 'value' => 0, 'name' => 'dsr'],
        //     ],
        //     [
        //         ['label' => 'Kratos', 'value' => 'Kratos', 'name' => 'name'],
        //         ['label' => '1.000,00', 'value' => 1200, 'name' => 'salario'],
        //         ['label' => '30', 'value' => 30, 'name' => 'dias_trabalhados'],
        //         ['label' => 'Ativo', 'value' => 1, 'name' => 'situacao'],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'cesta_basica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
        //         ['label' => '0', 'value' => 0, 'name' => 'numero_dependentes'],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'vale_transporte', "type" => "select"],
        //         ['label' => '6,90', 'value' => 6.9, 'name' => 'valor_transporte_valor_diario'],
        //         ['label' => '1.000,00', 'value' => 1200, 'name' => 'salario_bruto'],
        //         ['label' => '1.200,00', 'value' => 1400, 'name' => 'total_funcionario'],
        //         ['label' => '0',  'value' => 0, 'name' => 'dsr'],
        //     ],
        //     [
        //         ['label' => 'Batman', 'value' => 'Kratos', 'name' => 'name'],
        //         ['label' => '1.400,00', 'value' => 1200, 'name' => 'salario'],
        //         ['label' => '10', 'value' => 30, 'name' => 'dias_trabalhados'],
        //         ['label' => 'Afastado', 'value' => 2, 'name' => 'situacao'],
        //         ['label' => 'Não', 'value' => 0, 'name' => 'cesta_basica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_medica', "type" => "select"],
        //         ['label' => '3', 'value' => 3, 'name' => 'numero_dependentes'],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
        //         ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
        //         ['label' => 'Não', 'value' => 0, 'name' => 'vale_transporte', "type" => "select"],
        //         ['label' => '0', 'value' => 0, 'name' => 'valor_transporte_valor_diario'],
        //         ['label' => '466,67', 'value' => 466.67, 'name' => 'salario_bruto'],
        //         ['label' => '1.800,00', 'value' => 1400, 'name' => 'total_funcionario'],
        //         ['label' => '0',  'value' => 0, 'name' => 'dsr'],
        //     ],
        //     [
        //         ['label' => 'Total', 'value' => 'Total', 'name' => 'total_name'],
        //         ['label' => '3.600,00', 'value' => 3600, 'name' => 'total_salario'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_dias_trabalhados'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_situacao'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_cesta_basica'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_assistencia_medica'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_numero_dependentes'],
        //         ['label' => '', 'value' => 0, 'name' => 'total_assistencia_odontologica', "type" => "select"],
        //         ['label' => '', 'value' => 0, 'name' => 'total_contribuicao_sindical', "type" => "select"],
        //         ['label' => '', 'value' => 0, 'name' => 'total_vale_transporte', "type" => "select"],
        //         ['label' => '', 'value' => 0, 'name' => 'total_valor_transporte_valor_diario'],
        //         ['label' => '2.666,67', 'value' => 2666.67, 'name' => 'total_salario_bruto'],
        //         ['label' => '5.400,00', 'value' => 5400, 'name' => 'total_total_funcionario'],
        //         ['label' => '',  'value' => 0, 'name' => 'total_dsr'],
        //     ]
        // ]

        "rows" => [],
    ];

    public function handleParameter()
    {
        if (empty($this->parameter)) {
            return;
        }

        $params = Parameter::where('id', '=', $this->parameter)->first();

        $tmp_params = unserialize($params->parameters_value);

        $tmp_rows = [];

        foreach ($tmp_params['rows'] as $row) {
            $tmp_row = [];

            foreach ($row as $col) {

                $obj = [
                    'label' => $col['label'],
                    'value' => $col['value'],
                    'name' => $col['name']
                ];

                array_push($tmp_row, $obj);
            }

            array_push($tmp_rows, $tmp_row);
        }

        $tmp_params['rows'] = $tmp_rows;

        $this->parameters = $tmp_params;

        $this->calculateRows();
    }

    public function mount()
    {
        $params = Parameter::all();

        foreach ($params as $param) {
            array_push($this->parameters_options, [
                'label' => $param->name,
                'value' => $param->id
            ]);
        }

        $cc = session('preview')['cc'];

        if ($cc) {
            $employees = DB::connection('mysql_dump')
                ->table('FUNCIONARIOS')
                ->where('RA_CC', $cc)
                ->get();

            foreach ($employees as $row) {
                array_push($this->mo['rows'], [
                    [
                        'label' => $row->RA_NOME,
                        'value' => $row->RA_NOME,
                        'name' => 'name',
                        'align' => 'text-left'
                    ],
                    [
                        'label' => 'R$' . number_format($row->RA_SALARIO, 2, ',', '.'),
                        'value' => $row->RA_SALARIO,
                        'name' => 'salario'
                    ],
                    [
                        'label' => '30',
                        'value' => 30,
                        'name' => 'dias_trabalhados',
                        'type' => 'number'
                    ],
                    ['label' => 'Ativo', 'value' => 1, 'name' => 'situacao'],
                    ['label' => $row->R0_VLRCESTA > 0 ? 'Sim' : 'Não', 'value' => $row->R0_VLRCESTA > 0 ? 1 : 0, 'name' => 'cesta_basica', "type" => "select"],
                    ['label' => $row->RA_PLSAUDE > 0 ? 'Sim' : 'Não', 'value' => $row->RA_PLSAUDE > 0 ? $row->RA_PLSAUDE : 0, 'name' => 'assistencia_medica', "type" => "select"],
                    ['label' => $row->RB_QTDEPEN, 'value' => $row->RB_QTDEPEN, 'name' => 'numero_dependentes'],
                    ['label' => 'Sim', 'value' => 1, 'name' => 'assistencia_odontologica', "type" => "select"],
                    ['label' => 'Sim', 'value' => 1, 'name' => 'contribuicao_sindical', "type" => "select"],
                    ['label' => $row->R0_VLRVT > 0 ? 'Sim' : 'Não', 'value' => $row->R0_VLRVT > 0 ? 1 : 0, 'name' => 'vale_transporte', "type" => "select"],
                    ['label' => number_format($row->R0_VLRVT, 2, ',', '.'), 'value' => $row->R0_VLRVT, 'name' => 'valor_transporte_valor_diario'],
                    ['label' => '0,00', 'value' => 0, 'name' => 'salario_bruto'],
                    ['label' => '0,00', 'value' => 0, 'name' => 'total_funcionario'],
                    ['label' => '0', 'value' => 0, 'name' => 'dsr'],
                ]);
            }
        }

        $this->calculateRows();
    }

    public function calculateRows()
    {
        // dd($this->mo);

        foreach ($this->mo['rows'] as $row) {
        }
    }

    public function render()
    {
        return view('livewire.category.category-m-o');
    }
}
