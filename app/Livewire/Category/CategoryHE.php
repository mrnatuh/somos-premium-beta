<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoryHE extends Component
{

    public int $lastOfMonth;

    public $employees = [];

    public function updateQty($employeIndex, $rowIndex, $qtyIndex, $value)
    {
        $vlr = explode(":", $value);

        $hours = isset($vlr[0]) ? (int) $vlr[0] : 0;
        $minutes = isset($vlr[1]) ? (int) $vlr[1] : 0;
        $seconds = isset($vlr[2]) ? (int) $vlr[2] : 0;

        $str_hours = $hours > 9 ? $hours : '0' . $hours;
        $str_minutes = $minutes > 9 ? $minutes : '0' . $minutes;
        $str_seconds = $seconds > 9 ? $seconds : '0' . $seconds;

        $this->employees[$employeIndex]['rows'][$rowIndex][$qtyIndex]['value'] = "{$str_hours}:{$str_minutes}:{$str_seconds}";

        $this->sum();
    }

    public function sum()
    {
        for($ep = 0; $ep < sizeof($this->employees); $ep++) {
            $total_hr_50 = "00:00:00";
            $total_vlr_50 = 0.00;
            $arr_hr_50 = [];

            $total_hr_100 = "00:00:00";
            $total_vlr_100 = 0.00;
            $arr_hr_100 = [];

            $total_hr_faltas = "00:00:00";
            $total_vlr_faltas = 0.00;
            $arr_hr_faltas = [];

            $total_hr_atrasos = "00:00:00";
            $total_vlr_atrasos = 0.00;
            $arr_hr_atrasos = [];

            $total_hr_adicional_noturno = "00:00:00";
            $total_vlr_adicional_noturno= 0.00;
            $arr_hr_adicional_noturno = [];

            $normal_hours = 220;
            $vlr_salary = $this->employees[$ep]['salary_value'];

            for ($r = 0; $r < sizeof($this->employees[$ep]['rows']); $r++) {
                for ($c = 0; $c < sizeof($this->employees[$ep]['rows'][$r]); $c++) {
                    if ($this->employees[$ep]['rows'][$r][$c]['name'] == "hr_50") {
                        if (isset($this->employees[$ep]['rows'][$r][$c]['value']) && $this->employees[$ep]['rows'][$r][$c]['value'] != "") {
                            array_push($arr_hr_50, $this->employees[$ep]['rows'][$r][$c]['value']);
                        }
                    }

                    if ($this->employees[$ep]['rows'][$r][$c]['name'] == "hr_100") {
                        if (isset($this->employees[$ep]['rows'][$r][$c]['value']) && $this->employees[$ep]['rows'][$r][$c]['value'] != "") {
                            array_push($arr_hr_100, $this->employees[$ep]['rows'][$r][$c]['value']);
                        }
                    }

                    if ($this->employees[$ep]['rows'][$r][$c]['name'] == "hr_atrasos") {
                        if (isset($this->employees[$ep]['rows'][$r][$c]['value']) && $this->employees[$ep]['rows'][$r][$c]['value'] != "") {
                            array_push($arr_hr_atrasos, $this->employees[$ep]['rows'][$r][$c]['value']);
                        }
                    }

                    if ($this->employees[$ep]['rows'][$r][$c]['name'] == "hr_faltas") {
                        if (isset($this->employees[$ep]['rows'][$r][$c]['value']) && $this->employees[$ep]['rows'][$r][$c]['value'] != "") {
                            array_push($arr_hr_faltas, $this->employees[$ep]['rows'][$r][$c]['value']);
                        }
                    }

                    if ($this->employees[$ep]['rows'][$r][$c]['name'] == "hr_adicional_noturno") {
                        if (isset($this->employees[$ep]['rows'][$r][$c]['value']) && $this->employees[$ep]['rows'][$r][$c]['value'] != "") {
                            array_push($arr_hr_adicional_noturno, $this->employees[$ep]['rows'][$r][$c]['value']);
                        }
                    }
                }
            }

            // horas 50
            foreach($arr_hr_50 as $qty) {
                $total_hr = explode(':', $total_hr_50);
                $total_hours = (int) $total_hr[0];
                $total_minutes = (int) $total_hr[1];
                $total_seconds = (int) $total_hr[2];

                $tmp_hr = explode(":", $qty);
                $tmp_hours = isset($tmp_hr[0]) ? (int) $tmp_hr[0] : 0;
                $tmp_minutes = isset($tmp_hr[1]) ? (int) $tmp_hr[1] : 0;
                $tmp_seconds = isset($tmp_hr[2]) ? (int) $tmp_hr[2] : 0;

                $total_hours += $tmp_hours;
                $total_minutes += $tmp_minutes;
                $total_seconds += $tmp_seconds;

                $total_hours += floor($total_minutes / 60);
                $total_minutes = $total_minutes % 60;
                $total_hours += floor($total_seconds / 3600);
                $total_seconds = $total_seconds % 3600;

                $total_seconds_worked = ($total_hours * 3600) + ($total_minutes * 60) + $total_seconds;
                $total_hours_worked = ($total_seconds_worked / 3600);

                $vlr_extra_hour = 1.5;

                $tmp_total_salary = $vlr_salary / $normal_hours;
                $tmp_vlr_extra = ($tmp_total_salary * $vlr_extra_hour) * $total_hours_worked;

                $str_total_hours = $total_hours > 9 ? $total_hours : '0' . $total_hours;
                $str_total_minutes = $total_minutes > 9 ? $total_minutes : '0' . $total_minutes;
                $str_total_seconds = $total_seconds > 9 ? $total_seconds : '0' . $total_seconds;

                $total_hr_50 = "{$str_total_hours}:{$str_total_minutes}:{$str_total_seconds}";
                $total_vlr_50 = $tmp_vlr_extra;
            }

            $this->employees[$ep]['total_hr_50'] = $total_hr_50;
            $this->employees[$ep]['total_vlr_50'] = number_format($total_vlr_50, 2, ",", ".");

            // horas 100
            foreach ($arr_hr_100 as $qty) {
                $total_hr = explode(':', $total_hr_100);
                $total_hours = (int) $total_hr[0];
                $total_minutes = (int) $total_hr[1];
                $total_seconds = (int) $total_hr[2];

                $tmp_hr = explode(":", $qty);
                $tmp_hours = isset($tmp_hr[0]) ? (int) $tmp_hr[0] : 0;
                $tmp_minutes = isset($tmp_hr[1]) ? (int) $tmp_hr[1] : 0;
                $tmp_seconds = isset($tmp_hr[2]) ? (int) $tmp_hr[2] : 0;

                $total_hours += $tmp_hours;
                $total_minutes += $tmp_minutes;
                $total_seconds += $tmp_seconds;

                $total_hours += floor($total_minutes / 60);
                $total_minutes = $total_minutes % 60;
                $total_hours += floor($total_seconds / 3600);
                $total_seconds = $total_seconds % 3600;

                $total_seconds_worked = ($total_hours * 3600) + ($total_minutes * 60) + $total_seconds;
                $total_hours_worked = ($total_seconds_worked / 3600);

                $vlr_extra_hour = 2;

                $tmp_total_salary = $vlr_salary / $normal_hours;
                $tmp_vlr_extra = ($tmp_total_salary * $vlr_extra_hour) * $total_hours_worked;

                $str_total_hours = $total_hours > 9 ? $total_hours : '0' . $total_hours;
                $str_total_minutes = $total_minutes > 9 ? $total_minutes : '0' . $total_minutes;
                $str_total_seconds = $total_seconds > 9 ? $total_seconds : '0' . $total_seconds;

                $total_hr_100 = "{$str_total_hours}:{$str_total_minutes}:{$str_total_seconds}";
                $total_vlr_100 = $tmp_vlr_extra;
            }

            $this->employees[$ep]['total_hr_100'] = $total_hr_100;
            $this->employees[$ep]['total_vlr_100'] = number_format($total_vlr_100, 2, ",", ".");

            // Atrasos
            foreach ($arr_hr_atrasos as $qty) {
                $total_hr = explode(':', $total_hr_atrasos);
                $total_hours = (int) $total_hr[0];
                $total_minutes = (int) $total_hr[1];
                $total_seconds = (int) $total_hr[2];

                $tmp_hr = explode(":", $qty);
                $tmp_hours = isset($tmp_hr[0]) ? (int) $tmp_hr[0] : 0;
                $tmp_minutes = isset($tmp_hr[1]) ? (int) $tmp_hr[1] : 0;
                $tmp_seconds = isset($tmp_hr[2]) ? (int) $tmp_hr[2] : 0;

                $total_hours += $tmp_hours;
                $total_minutes += $tmp_minutes;
                $total_seconds += $tmp_seconds;

                $total_hours += floor($total_minutes / 60);
                $total_minutes = $total_minutes % 60;
                $total_hours += floor($total_seconds / 3600);
                $total_seconds = $total_seconds % 3600;

                $total_seconds_worked = ($total_hours * 3600) + ($total_minutes * 60) + $total_seconds;
                $total_hours_worked = ($total_seconds_worked / 3600);

                $tmp_total_salary = $vlr_salary / $normal_hours;
                $tmp_vlr_extra = $tmp_total_salary * $total_hours_worked;

                $str_total_hours = $total_hours > 9 ? $total_hours : '0' . $total_hours;
                $str_total_minutes = $total_minutes > 9 ? $total_minutes : '0' . $total_minutes;
                $str_total_seconds = $total_seconds > 9 ? $total_seconds : '0' . $total_seconds;

                $total_hr_atrasos = "{$str_total_hours}:{$str_total_minutes}:{$str_total_seconds}";
                $total_vlr_atrasos = $tmp_vlr_extra;
            }

            $this->employees[$ep]['total_hr_atrasos'] = $total_hr_atrasos;
            $this->employees[$ep]['total_vlr_atrasos'] = number_format($total_vlr_atrasos, 2, ",", ".");

            // Faltas
            foreach ($arr_hr_faltas as $qty) {
                $total_hr = explode(':', $total_hr_faltas);
                $total_hours = (int) $total_hr[0];
                $total_minutes = (int) $total_hr[1];
                $total_seconds = (int) $total_hr[2];

                $tmp_hr = explode(":", $qty);
                $tmp_hours = isset($tmp_hr[0]) ? (int) $tmp_hr[0] : 0;
                $tmp_minutes = isset($tmp_hr[1]) ? (int) $tmp_hr[1] : 0;
                $tmp_seconds = isset($tmp_hr[2]) ? (int) $tmp_hr[2] : 0;

                $total_hours += $tmp_hours;
                $total_minutes += $tmp_minutes;
                $total_seconds += $tmp_seconds;

                $total_hours += floor($total_minutes / 60);
                $total_minutes = $total_minutes % 60;
                $total_hours += floor($total_seconds / 3600);
                $total_seconds = $total_seconds % 3600;

                $total_seconds_worked = ($total_hours * 3600) + ($total_minutes * 60) + $total_seconds;
                $total_hours_worked = ($total_seconds_worked / 3600);

                $tmp_total_salary = $vlr_salary / $normal_hours;
                $tmp_vlr_extra = $tmp_total_salary * $total_hours_worked;

                $str_total_hours = $total_hours > 9 ? $total_hours : '0' . $total_hours;
                $str_total_minutes = $total_minutes > 9 ? $total_minutes : '0' . $total_minutes;
                $str_total_seconds = $total_seconds > 9 ? $total_seconds : '0' . $total_seconds;

                $total_hr_faltas = "{$str_total_hours}:{$str_total_minutes}:{$str_total_seconds}";
                $total_vlr_faltas = $tmp_vlr_extra;
            }


            $this->employees[$ep]['total_hr_faltas'] = $total_hr_faltas;
            $this->employees[$ep]['total_vlr_faltas'] =
            number_format($total_vlr_faltas, 2, ",", ".");;


            // Adicional Noturno
            foreach ($arr_hr_adicional_noturno as $qty) {
                $total_hr = explode(':', $total_hr_adicional_noturno);
                $total_hours = (int) $total_hr[0];
                $total_minutes = (int) $total_hr[1];
                $total_seconds = (int) $total_hr[2];

                $tmp_hr = explode(":", $qty);
                $tmp_hours = isset($tmp_hr[0]) ? (int) $tmp_hr[0] : 0;
                $tmp_minutes = isset($tmp_hr[1]) ? (int) $tmp_hr[1] : 0;
                $tmp_seconds = isset($tmp_hr[2]) ? (int) $tmp_hr[2] : 0;

                $total_hours += $tmp_hours;
                $total_minutes += $tmp_minutes;
                $total_seconds += $tmp_seconds;

                $total_hours += floor($total_minutes / 60);
                $total_minutes = $total_minutes % 60;
                $total_hours += floor($total_seconds / 3600);
                $total_seconds = $total_seconds % 3600;

                $total_seconds_worked = ($total_hours * 3600) + ($total_minutes * 60) + $total_seconds;
                $total_hours_worked = ($total_seconds_worked / 3600);

                $normal_hours = 220;
                $vlr_salary = $this->employees[$ep]['salary_value'];

                $tmp_total_salary = $vlr_salary / $normal_hours;
                $tmp_vlr_extra = $tmp_total_salary * $total_hours_worked;
                $tmp_vlr_extra += ($tmp_vlr_extra * (35/100));

                $str_total_hours = $total_hours > 9 ? $total_hours : '0' . $total_hours;
                $str_total_minutes = $total_minutes > 9 ? $total_minutes : '0' . $total_minutes;
                $str_total_seconds = $total_seconds > 9 ? $total_seconds : '0' . $total_seconds;

                $total_hr_adicional_noturno = "{$str_total_hours}:{$str_total_minutes}:{$str_total_seconds}";
                $total_vlr_adicional_noturno = $tmp_vlr_extra;
            }

            $this->employees[$ep]['total_hr_adicional_noturno'] = $total_hr_adicional_noturno;
            $this->employees[$ep]['total_vlr_adicional_noturno'] =
            number_format($total_vlr_adicional_noturno, 2, ",", ".");;
        }

        $this->save();
    }

    public function mount()
    {
        $weekref = session('preview')['week_ref'] ?? null;
        $cc  = session('preview')['cc'] ?? null;

        if ($weekref) {
            preg_match('/(\d{2})(\d{2})(\d{2})/', $weekref, $matches, PREG_OFFSET_CAPTURE);

            $month = $matches[1][0];
            // $week = $matches[2][0];
            $year = $matches[3][0];

            $this->lastOfMonth = (int) Carbon::parse("{$year}-{$month}-01")->lastOfMonth()->format('d');
        }

        // caso exista no banco
        if ($cc) {
            $he = Option::where([['cc', '=', $cc], ['week_ref', '=', $weekref], ['option_name', '=', 'he']])->first();


            if ($he) {
                $this->employees = unserialize($he->option_value);
                return;
            }
        }

        $tmp_employees = null;

        if ($cc) {
            $tmp_employees = DB::connection('mysql_dump')
                ->table('FUNCIONARIOS')
                ->where('RA_CC', $cc)
                ->get();

            foreach($tmp_employees as $row) {
                $add = [
                    "id" => trim($row->RA_ID),

                    "title" => trim($row->RA_NOME),

                    "salary_label" => number_format($row->RA_SALARIO, 2, ',', '.'),
                    "salary_value" => $row->RA_SALARIO,

                    "colspan" => 5,
                    "rowspan" => $this->lastOfMonth,
                    "last_of_month" => $this->lastOfMonth,

                    "total_hr_50" => "00:00:00",
                    "total_hr_100" => "00:00:00",
                    "total_hr_atrasos" => "00:00:00",
                    "total_hr_faltas" => "00:00:00",
                    "total_hr_adicional_noturno" => "00:00:00",

                    "total_vlr_50" => "0,00",
                    "total_vlr_100" => "0,00",
                    "total_vlr_atrasos" => "0,00",
                    "total_vlr_faltas" => "0,00",
                    "total_vlr_adicional_noturno" => "0,00",

                    "columns" => [
                        [
                            "label" => "50%"
                        ],
                        [
                            "label" => "100%"
                        ],
                        [
                            "label" => "Atraso"
                        ],
                        [
                            "label" => "Falta"
                        ],
                        [
                            "label" => "Ad. Noturno"
                        ]
                    ],

                    "rows" => []
                ];

                for($d = 1; $d <= $this->lastOfMonth; $d++) {
                    array_push($add['rows'], [
                        [
                            "name" => "hr_50",
                            "value" => "",
                            "label" => ""
                        ],
                        [
                            "name" => "hr_100",
                            "value" => "",
                            "label" => ""
                        ],
                        [
                            "name" => "hr_atrasos",
                            "value" => "",
                            "label" => ""
                        ],
                        [
                            "name" => "hr_faltas",
                            "value" => "",
                            "label" => ""
                        ],
                        [
                            "name" => "hr_adicional_noturno",
                            "value" => "",
                            "label" => ""
                        ]
                    ]);
                }

                array_push($this->employees, $add);
            }
        }
    }

    public function to_float($val)
    {
        $val = str_replace(",", ".", $val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return floatval($val);
    }

    public function save()
    {
        if (sizeof($this->employees) == 0) {
            return;
        }

        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = 0.00;
        $preview->he = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->employees);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'he',
            ],
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'he',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        // Update para calcular MO depois de salvar HE.
        $mo = Option::where([
            'cc' => $cc,
            'week_ref' => $weekref,
            'option_name' => 'mo',
        ])->first();

        if ($mo) {
            $content_mo = unserialize($mo->option_value);

            $tmp_he = Option::where([
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'he',
            ])->first();

            if ($tmp_he) {

                $he = unserialize($tmp_he->option_value);

                foreach($content_mo->employees as $item){
                    $vlr_salario_bruto = ($item->salario / 30) * $item-> dias_trabalhados;

                    $item->vlr_salario_bruto = number_format($vlr_salario_bruto, 2, ',', '.');

                    $item->dias_trabalhados;

                    $item->vlr_salario = number_format($item->salario, 2, ',', '.');

                    $extras = array_filter($he, function($var) use($item) {
                        return $var['id'] == $item->id;
                    });

                    $vlr_total_50 =  0;
                    $vlr_total_100 = 0;
                    $vlr_total_faltas = 0;
                    $vlr_total_atrasos = 0;
                    $vlr_total_adicional_noturno = 0;
                    $dsr = 0;
                    $vlr_total_he = 0;

                    if (sizeof($extras) > 0 && isset($extras[0])) {
                        $vlr_total_50 = $this->to_float($extras[0]['total_vlr_50']) ?? 0;

                        $vlr_total_100 = $this->to_float($extras[0]['total_vlr_100']) ?? 0;

                        $vlr_total_adicional_noturno = $this->to_float($extras[0]['total_vlr_adicional_noturno']) ?? 0;

                        $vlr_he_total_add = $vlr_total_50 + $vlr_total_100 + $vlr_total_adicional_noturno;

                        $vlr_total_faltas =
                        $this->to_float($extras[0]['total_vlr_faltas']) ?? 0;

                        $vlr_total_atrasos =
                        $this->to_float($extras[0]['total_vlr_atrasos']) ?? 0;

                        $vlr_he_total_sub = $vlr_total_faltas + $vlr_total_atrasos;

                        $dsr = ($vlr_total_50 / $content_mo->dias_seg_sab) * $content_mo->dias_dom_fer;

                        $dsr += ($vlr_total_100 / $content_mo->dias_seg_sab) * $content_mo->dias_dom_fer;

                        $dsr += ($vlr_total_adicional_noturno / $content_mo->dias_seg_sab) * $content_mo->dias_dom_fer;

                        $item->vlr_total_he = $vlr_he_total_add - $vlr_he_total_sub;
                    }

                    $item->vlr_dsr = number_format($dsr, 2, ',', '.');

                    $item->vlr_salario_bruto = number_format($vlr_salario_bruto, 2, ',', '.');

                    $vlr_vr = $vlr_salario_bruto * (1 / 100);
                    $item->vlr_desconto_refeicao = number_format($vlr_vr, 2, ',', '.');

                    $vlr_vt = $vlr_salario_bruto * (5.5 / 100);
                    $item->vlr_desconto_vale_transporte = number_format( $vlr_vt, 2, ',', '.');

                    $item->vrl_vale_transporte = $item->vlr_vt == "0,01" ? $item->vlr_desconto_vale_transporte : number_format($item->vlr_vt, 2, ',', '.');

                    $vlr_cesta_basica = $content_mo->params->cesta_basica * $item->option_cesta_basica;

                    $item->vlr_cesta_basica = number_format($vlr_cesta_basica, 2, ',', '.');

                    $vlr_assistencia_medica_funcionarios = $item->plano_saude * $content_mo->params->assistencia_medica_titular;

                    $item->vlr_assistencia_medica_funcionario = number_format($vlr_assistencia_medica_funcionarios, 2, ',', '.');

                    $vlr_assistencia_medica_dependentes = $item->qtde_dependentes * $content_mo->params->assistencia_medica_dependentes;

                    $vlr_total_assistencia_medica = ($vlr_assistencia_medica_funcionarios + $vlr_assistencia_medica_dependentes) * $item->option_assistencia_medica;

                    $item->vlr_assistencia_medica_dependentes = number_format($vlr_total_assistencia_medica, 2, ',', '.');

                    $vlr_exames = 0 * $content_mo->params->exames;
                    $item->vlr_exames = number_format($vlr_exames, 2, ',', '.');

                    $vlr_assistencia_odontologica = ($item->odonto + $item->odonto_dependentes) * $content_mo->params->assistencia_odontologica;

                    $item->vlr_assistencia_odontologica = number_format( $vlr_assistencia_odontologica, 2, ',', '.');

                    $item->vlr_contribuicao_sindical = number_format(($item->contribuicao_sindical * $content_mo->params->contribuicao_sindical), 2, ',', '.');

                    $item->vlr_inss = number_format(($content_mo->params->inss / 100) * ($dsr + $vlr_salario_bruto), 2, ',', '.');

                    $item->vlr_fgts = number_format((($content_mo->params->fgts / 100) * ($dsr + $vlr_salario_bruto)), 2, ',', '.');

                    $item->vlr_provisao_ferias = number_format((($content_mo->params->provisao_ferias / 100) * ($dsr + $vlr_salario_bruto)), 2, ',', '.');

                    $item->vlr_decimo_terceiro = number_format((($content_mo->params->provisao_decimo_terceiro / 100) * ($dsr + $vlr_salario_bruto)), 2, ',', '.');

                    $vlr_total_salario = $vlr_salario_bruto + $dsr - $vlr_vt - $vlr_vr;

                    $item->vlr_total_salario = number_format( $vlr_total_salario, 2, ',', '.');

                    $vlr_total_funcionario = $vlr_total_salario + $vlr_total_he + $vlr_cesta_basica + $vlr_total_assistencia_medica + $vlr_exames + $vlr_assistencia_odontologica;

                    $item->tmp_vlr_total_funcionario = number_format( $vlr_total_funcionario, 2, ',', '.');

                    $item->vlr_total_funcionario = number_format( $vlr_total_funcionario, 2, ',', '.');
                }

                $mo->option_value = serialize($content_mo);

                $mo->update();
            }
        }

        $this->dispatch('update-bar-total', [
            'cc' => $cc,
            'weekref' => $weekref
        ]);
    }

    public function render()
    {
        return view('livewire.category.category-h-e');
    }
}
