<?php

namespace App\Livewire\Category;

use App\Models\Invoicing;
use App\Models\Option;
use App\Models\Preview;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;

class CategoryInvoicing extends Component
{
    public $deleteItem = [];

    public $deleteCompanyColumn = [];

    public $lastOfMonth = 0;

    public $companies = [];

    public function updateQty($companyIndex, $rowIndex, $qtyIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            if (isset($this->companies[$companyIndex]['rows'])) {
                $this->companies[$companyIndex]['rows'][$rowIndex][$qtyIndex]['value'] = (int) $value ?? 0;
            }
        }
    }

    public function addColumnPrice($companyIndex, $value)
    {
        if (isset($this->companies[$companyIndex])) {
            // cadastra novo label de preco
            array_push($this->companies[$companyIndex]['labels'], $value);

            // atualiza o valor
            array_push($this->companies[$companyIndex]['prices'], $this->companies[$companyIndex]['prices_vlr'][Str::slug($value)]);

            // adiciona o preco selecionado
            array_push($this->companies[$companyIndex]['prices_selected'], $value);

            // adiciona mais 1 coluna
            $this->companies[$companyIndex]['colspan'] += 1;

            // atualiza a lista de quantidade
            for ($r = 0; $r < sizeof($this->companies[$companyIndex]['rows']); $r++) {
                array_push($this->companies[$companyIndex]['rows'][$r], [
                    'value' => 0,
                ]);
            }

            $this->save();
        }
    }

    #[On('search-add-client')]
    public function addClient($client_id)
    {
        $client = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where('A1_COD', $client_id)
            ->first();

        if (!$client) {
            return;
        }

        $exists = false;

        foreach ($this->companies as $company) {
            if ($company['id'] === $client->A1_CGC) {
                $exists = true;
            }
        }

        $precos_vlr = [];
        $precos_options = [];

        if (isset($client->A1_TABELA)) {
            $prices = DB::connection('mysql_dump')
                ->table('TABELAPRECO')
                ->where('DA0_CODTAB', $client->A1_TABELA)
                ->get();

            foreach ($prices as $row) {
                $preco_option = Str::slug(trim($row->B1_DESC));

                array_push($precos_options, [
                    "value" => $preco_option,
                    "label" => trim($row->B1_DESC),
                ]);

                $precos_vlr[$preco_option] = ['value' => $row->DA1_PRCVEN,  'edit' => true];
            }
        }

        if ($exists) {
            session()->flash('message', [
                'type' => 'warning',
                'message' => 'Cliente já adicionado.'
            ]);

            return;
        }

        $add_client = [
            "id" => trim($client->A1_CGC),

            "title" => trim($client->A1_NOME),

            "colspan" => 1,
            "rowspan" => $this->lastOfMonth,
            "last_of_month" => $this->lastOfMonth,

            "prices_selected" => [],
            "prices_vlr" => $precos_vlr,
            "prices_options" => $precos_options,

            "labels" => [],
            "prices" => [],

            "rows" => []
        ];

        for ($i = 0; $i < $add_client['rowspan']; $i++) {
            $cols = [];

            for ($c = 0; $c < $add_client['colspan']; $c++) {
                array_push($cols, [
                    'value' => 0,
                ]);
            }

            array_push($add_client['rows'], $cols);
        }

        array_push($this->companies, $add_client);

        $this->save();
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->companies as $company) {
            $prices = $company['prices'] ?? [];
            $rows = $company['rows'] ?? [];
            $colspan = sizeof($prices);
            $rowspan = $company['rowspan'];

            if (sizeof($prices)) {
                for ($c = 0; $c < $colspan; $c++) {
                    $price = $prices[$c]['value'] ?? false;
                    if ($price) {
                        for ($r = 0; $r < $rowspan; $r++) {
                            $qty = $rows[$r][$c]['value'];
                            $total += $price * $qty;
                        }
                    }
                }
            }
        }

        return $total;
    }

    public function deleteRowItem($id)
    {
        $this->deleteItem[$id] = true;
    }

    public function cancelDeleteItem($id)
    {
        $this->deleteItem[$id] = false;
    }

    public function confirmDeleteItem($id)
    {
        $rowIndex = -1;

        foreach ($this->companies as $key => $company) {
            if ($company['id'] === $id) {
                $rowIndex = $key;
            }
        }

        if ($rowIndex > -1) {
            unset($this->companies[$rowIndex]);
        }

        return true;
    }

    public function deleteColumnItem($companyIndex, $columnIndex)
    {
        if (!isset($this->deleteCompanyColumn[$companyIndex])) {
            $this->deleteCompanyColumn[$companyIndex] = [];
        }

        $this->deleteCompanyColumn[$companyIndex][$columnIndex] = true;
    }

    public function cancelDeleteColumnItem($companyIndex, $columnIndex)
    {
        if (!isset($this->deleteCompanyColumn[$companyIndex])) {
            $this->deleteCompanyColumn[$companyIndex] = [];
        }

        $this->deleteCompanyColumn[$companyIndex][$columnIndex] = false;
    }

    public function confirmDeleteColumnItem($companyIndex, $labelIndex)
    {
        if (isset($this->companies[$companyIndex])) {
            unset($this->companies[$companyIndex]['labels'][$labelIndex]);

            unset($this->companies[$companyIndex]['prices'][$labelIndex]);

            unset($this->companies[$companyIndex]['prices_selected'][$labelIndex]);

            $this->companies[$companyIndex]['colspan'] -= 1;

            // update rows
            for ($r = 0; $r < sizeof($this->companies[$companyIndex]['rows']); $r++) {
                unset($this->companies[$companyIndex]['rows'][$r][$labelIndex]);
                $this->companies[$companyIndex]['rows'][$r] = array_values($this->companies[$companyIndex]['rows'][$r]);
            }

            $this->deleteCompanyColumn[$companyIndex][$labelIndex] = false;

            $this->save();
        }
    }

    public function save()
    {
        $weekref = session('preview')['week_ref'];
        $cc = session('preview')['cc'];

        // acha o preview
        $preview = Preview::where('week_ref', $weekref)->first();

        // calcula o total
        $total = number_format($this->getTotal(), 2);
        $preview->invoicing = $total;
        $preview->save();

        // serializa o conteúdo
        $content = serialize($this->companies);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'faturamento',
            ],
            [
                'cc' => $cc,
                'week_ref' => $weekref,
                'option_name' => 'faturamento',
                'option_value' => $content,
                'total' => $total,
            ]
        );

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);

        return $this->redirect('/categoria?filter=faturamento', navigate: true);
    }

    public function mount()
    {
        $this->lastOfMonth = (int) Carbon::now()->lastOfMonth()->format('d');

        $weekref = session('preview')['week_ref'];

        $faturamento = Option::where('week_ref', $weekref)
            ->where('option_name', 'faturamento')
            ->first();

        if ($faturamento) {
            $this->companies = unserialize($faturamento->option_value);
        }
    }

    public function render()
    {
        return view('livewire.category.category-invoicing');
    }
}
