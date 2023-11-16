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
        $preview->he = $total ;
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
    }

    public function render()
    {
        return view('livewire.category.category-h-e');
    }
}
