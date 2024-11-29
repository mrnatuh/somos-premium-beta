<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\Preview;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SimpleExport;

class PreviewExport extends Controller
{
  protected $sheets;

  public function __invoke(Request $request, int $id)
  {
    $preview = Preview::where('id', $id)->first();

    $options = Option::where([
      'cc' => $preview->cc,
      'week_ref' => $preview->week_ref,
    ])->get();

    $data = [];

    foreach ($options as $option) {
      if ($option->option_name == 'faturamento') {
        $content = unserialize($option->option_value);

        $rows = [];

        // Primeira linha - Nomes dos clientes
        $firstRow = ['Dia'];
        // Segunda linha - Tipos de refeição
        $secondRow = [''];
        // Terceira linha - Preços
        $thirdRow = ['Preço'];

        foreach ($content as $client) {
          // Filtra os itens, removendo o tipo "OUTRO"
          $refeicoes = array_filter($client['rows'][0], function ($item) {
            return strtoupper($item['name']) !== 'OUTRO';
          });

          $numColumns = count($refeicoes);

          // Adiciona o nome do cliente
          $firstRow[] = $client['title'];
          // Preenche o resto do colspan com células vazias
          $firstRow = array_pad($firstRow, count($firstRow) + $numColumns - 1, '');

          // Adiciona os tipos de refeição
          foreach ($refeicoes as $item) {
            $secondRow[] = strtoupper($item['name']);
          }

          // Adiciona os preços
          foreach ($refeicoes as $item) {
            $price = array_values(array_filter($client['prices_vlr'], function ($p) use ($item) {
              return strtolower($p['name']) === strtolower($item['name']);
            }))[0] ?? ['value' => 0];

            $thirdRow[] = 'R$ ' . number_format($price['value'], 2, ',', '.');
          }
        }

        $rows[] = $firstRow;
        $rows[] = $secondRow;
        $rows[] = $thirdRow;

        for ($day = 0; $day < $content[0]['last_of_month']; $day++) {
          $rowData = [($day + 1)]; // Dia

          foreach ($content as $client) {
            // Filtra os itens do dia, removendo o tipo "OUTRO"
            $dayItems = array_filter($client['rows'][$day], function ($item) {
              return strtoupper($item['name']) !== 'OUTRO';
            });

            foreach ($dayItems as $item) {
              $rowData[] = $item['value'];
            }
          }

          $rows[] = $rowData;
        }

        $data['Faturamento'] = [
          'data' => $rows,
        ];
      }

      if ($option->option_name == 'mp') {
        $content = unserialize($option->option_value);

        $rows = [];

        foreach ($content['rows'] as $row) {
          $values = [];
          foreach ($row as $item) {
            $values[] = $item['value'];
          }
          $rows[] = $values;
        }

        $data['MP'] = [
          'headers' => $content['labels'],
          'data' => $rows,
        ];
      }

      if ($option->option_name == 'eventos') {
        $content = unserialize($option->option_value);

        $rows = [];

        foreach ($content['rows'] as $row) {
          $values = [];
          foreach ($row as $item) {
            $values[] = $item['value'];
          }
          $rows[] = $values;
        }

        $data['Eventos'] = [
          'headers' => $content['labels'],
          'data' => $rows,
        ];
      }

      if ($option->option_name == 'mo') {
        $content = unserialize($option->option_value);

        $headers =
          [
            'Nome',
            'Salário Base',
            'Dias Trabalhados',
            'Salário Bruto',
            'INSS',
            'FGTS',
            'Vale Transporte',
            'Vale Refeição',
            'Cesta Básica',
            'Assist. Médica Func.',
            'Assist. Médica Dep.',
            'Exames',
            'Contrib. Sindical',
            'Provisão Férias',
            'Provisão 13º',
            'Custo Total'
          ];

        $rows = [];

        foreach ($content->employees as $funcionario) {

          $rows[] = [
            $funcionario->nome,
            $funcionario->salario,
            $funcionario->dias_trabalhados,
            $funcionario->vlr_salario_bruto,
            $funcionario->vlr_inss,
            $funcionario->vlr_fgts,
            $funcionario->vrl_vale_transporte,
            $funcionario->vlr_desconto_refeicao,
            $funcionario->vlr_cesta_basica,
            $funcionario->vlr_assistencia_medica_funcionario,
            $funcionario->vlr_assistencia_medica_dependentes,
            $funcionario->vlr_exames,
            $funcionario->vlr_contribuicao_sindical,
            $funcionario->vlr_provisao_ferias,
            $funcionario->vlr_decimo_terceiro,
            $funcionario->vlr_total_funcionario
          ];
        }

        $data['MO'] = [
          'headers' => $headers,
          'data' => $rows,
        ];
      }

      if ($option->option_name == 'gd') {
        $content = unserialize($option->option_value);

        $rows = [];

        foreach ($content['rows'] as $row) {
          $values = [];
          foreach ($row as $item) {
            $values[] = $item['value'];
          }
          $rows[] = $values;
        }

        $data['GD'] = [
          'headers' => $content['labels'],
          'data' => $rows,
        ];
      }
    }

    return Excel::download(new SimpleExport($data), 'previa_' . $preview->cc . '_' . $preview->week_ref . '.xlsx');
  }
}
