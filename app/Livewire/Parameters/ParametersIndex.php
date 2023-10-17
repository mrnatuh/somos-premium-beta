<?php

namespace App\Livewire\Parameters;

use App\Models\Parameter;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;

class ParametersIndex extends Component
{
    #[Rule('required|string|min:3')]
    public string $name = '';

    public $results = [];

    public $parameters = [
        'labels' => [
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
                    'label' => '190,00',
                    'value' => 190,
                    'name' => 'cesta_basica',
                    'type' => 'number',
                ],
                [
                    'label' => '430,00',
                    'value' => 430,
                    'name' => 'assistencia_medica_titular',
                    'type' => 'number',
                ],
                [
                    'label' => '150,00',
                    'value' => 150,
                    'name' => 'assistencia_medica_dependentes',
                    'type' => 'number',
                ],
                [
                    'label' => '22,50',
                    'value' => 22.5,
                    'name' => 'exames',
                    'type' => 'number',
                ],
                [
                    'label' => '12,45',
                    'value' => 12.45,
                    'name' => 'assistencia_odontologica',
                    'type' => 'number',
                ],
                [
                    'label' => '50',
                    'value' => 50,
                    'name' => 'contribuicao_sindical',
                    'type' => 'number',
                ],
                [
                    'label' => '28,80%',
                    'value' => 28.8,
                    'name' => 'inss',
                    'type' => 'number',
                ],
                [
                    'label' => '8%',
                    'value' => 8,
                    'name' => 'fgts',
                    'type' => 'number',
                ],
                [
                    'label' => '15,10%',
                    'value' => 15.1,
                    'name' => 'provisao_ferias',
                    'type' => 'number',
                ],
                [
                    'label' => '11,40%',
                    'value' => 11.4,
                    'name' => 'provisao_decimo_terceiro',
                    'type' => 'number',
                ],
                [
                    'label' => '0',
                    'value' => 0,
                    'name' => 'taxa_vale_transporte',
                    'type' => 'number',
                ]
            ]
        ]
    ];

    public function updateRow($rowIndex, $columnIndex, $value)
    {
        $this->parameters['rows'][$rowIndex][$columnIndex]['label'] = "'" . number_format($value, 2, ',', '.') . "'";
        $this->parameters['rows'][$rowIndex][$columnIndex]['value'] = $value;
    }

    public function save()
    {
        $this->validate();

        $slug = Str::slug($this->name);

        $parameter = Parameter::where('slug', '=', $slug)->first();

        if ($parameter) {
            return back()->with('error', 'Já existe um parâmetro cadastrado com esse nome');
        }

        Parameter::create([
            'name' => $this->name,
            'slug' => $slug,
            'parameters_value' => serialize($this->parameters),
        ]);

        return redirect('/categoria/parametros')->with('success', 'Parâmetros criados com sucesso');
    }

    public function mount()
    {
        $this->results = Parameter::all();
    }

    public function render()
    {
        return view('livewire.parameters.parameters-index');
    }
}
