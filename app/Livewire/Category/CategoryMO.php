<?php

namespace App\Livewire\Category;

use App\Models\CostsParams;
use App\Models\Option;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryMO extends Component
{
    public int $lastOfMonth;

    public $json = '';

    public function render()
    {
        $cc = session('preview')['cc'];
        $weekref = session('preview')['week_ref'];
        $dias_seg_sab = 31;
        $dias_dom_fer = 0;

        $mo = Option::where([
            'cc' => $cc,
            'week_ref' => $weekref,
            'option_name' => 'mo',
        ])->first();

        if ($mo) {

            $content = unserialize($mo->option_value);
            $parameters = $content->params;
            $employees = $content->employees;
            $dias_seg_sab = $content->dias_seg_sab;
            $dias_dom_fer = $content->dias_dom_fer;

        } else {
            $param = CostsParams::where('costs_center_id', $cc)->first();

            $tmp_parameters = Parameter::where('id', $param->param_id)->first();
            $tmp_parameters= unserialize($tmp_parameters->parameters_value);

            $parameters = [];
            foreach($tmp_parameters['rows'] as $key => $value) {
                foreach ($value as $item) {
                    $parameters[$item['name']] = $item['value'];
                }
            }

            // TODO: trazer os funcionários e dados da prévia anterior, se estiver anquele mês.

            $employees = [];
            if ($cc) {
                $tmp_employees = DB::connection('mysql_dump')
                    ->table('FUNCIONARIOS')
                        ->where('RA_CC', $cc)
                        ->get();

                foreach($tmp_employees as $item) {
                    array_push($employees, [
                        "id" => trim($item->RA_ID),
                        'status' => 1,
                        'nome' => trim($item->RA_NOME),
                        'salario' => $item->RA_SALARIO,
                        'plano_saude' => $item->RA_PLSAUDE,
                        'qtde_dependentes' => (int) $item->RB_QTDEPEN ?? 0,
                        'vlr_plano' => (int) $item->RD_VLRPLAN ?? 0,
                        'vlr_dependentes' => (int) $item->RD_VLRDEPEND ?? 0,
                        'odonto' => (float) $item->RD_VLRODONTO ?? 0,
                        'odonto_dependentes' => (float) $item->RD_VLRODONTODEP ?? 0,
                        'vlr_vt' => (int) $item->R0_VLRVT ?? 0,
                        'vlr_cesta' => (int) $item->R0_VLRCESTA ?? 0,
                        'dias_trabalhados' => $this->lastOfMonth,
                        'contribuicao_sindical' => 1,
                        'assistencia_odontologica' => 0,
                        'vlr_salario_bruto' => 0,
                        'option_cesta_basica' => !empty($item->R0_VLRCESTA) ? 1 : 0,
                        'option_assistencia_medica' => !empty($item->RA_PLSAUDE) ? 1 : 0,
                        'option_assistencia_odontologica' => !empty($item->RD_VLRODONTO) ? 1 : 0,
                        'option_contribuicao_sindical' => 1,
                        'option_vale_transporte' => !empty($item->R0_VLRVT) ? 1 : 0,
                    ]);
                }
            }
        }

        if ($weekref) {
            preg_match('/(\d{2})(\d{2})(\d{2})/', $weekref, $matches, PREG_OFFSET_CAPTURE);

            $month = $matches[1][0];
            // $week = $matches[2][0];
            $year = $matches[3][0];

            $this->lastOfMonth = (int) Carbon::parse("{$year}-{$month}-01")->lastOfMonth()->format('d');

            $tmp_he = Option::where(
                [
                    'cc' => $cc,
                    'week_ref' => $weekref,
                    'option_name' => 'he',
                ]
            )->first();

            $arr_he = [];
            $he = [];

            if ($tmp_he) {
                $he = unserialize($tmp_he->option_value);

                foreach ($he as $key => $value) {
                    array_push($arr_he, [
                        "id" => trim($value['id']),
                        "total_vlr_50" => $value['total_vlr_50'],
                        "total_vlr_100" => $value['total_vlr_100'],
                        "total_vlr_adicional_noturno" => $value['total_vlr_adicional_noturno'],
                        "total_vlr_atrasos" => $value['total_vlr_atrasos'],
                        "total_vlr_faltas" => $value['total_vlr_faltas'],
                    ]);
                }
            }
        }

        return view('livewire.category.category-m-o', [
            'mo' => [
                'cc' => $cc,
                'weekref' => $weekref,
                'dias_seg_sab' => $dias_seg_sab,
                'dias_dom_fer' => $dias_dom_fer,
                'parameters' => $parameters,
                'employees' => $employees,
                'he' => $arr_he,
            ]
        ]);
    }
}
