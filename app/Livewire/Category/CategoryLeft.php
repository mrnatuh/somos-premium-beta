<?php

namespace App\Livewire\Category;

use App\Models\Option;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class CategoryLeft extends Component
{
    public $edit = true;

    public $results = [];

    public $cc;

    public $weekref;

    public $lastOfMonth;

    public $clients;

    public $month = '';

    public $day = '';
    public $client = '';
    public $service = '';
    public $qty = 0;

    public $total_qty = 0;
    public $clientSelected = null;

    public $prices = [];
    public $filtered_prices = [];

    public function deleteClient($index)
    {
        $tmp_results = [];

        foreach ($this->results as $key => $value) {
            if ($key != $index) {
                array_push($tmp_results, $value);
            }
        }

        $this->results = $tmp_results;

        $this->getTotal();

        $this->save();
    }

    #[On('left-client-selected')]
    public function getPrices()
    {
        $id_client = explode("_", $this->client)[1];

        $client = null;
        foreach ($this->clients as $c) {
            if ($c->A1_CGC == $id_client) {
                $client = $c;
            }
        }

        $this->filtered_prices = [];

        if ($client) {
            $tmp_prices = [];
            foreach ($this->prices as $p) {
                if ($p->DA0_CODTAB == $client->A1_TABELA) {
                    array_push($tmp_prices, $p);
                }
            }

            $this->filtered_prices = $tmp_prices;
        }
    }

    public function addClient()
    {
        $validated = $this->validate([
            'day' => 'required',
            'client' => 'required',
            'service' => 'required',
            'qty' => 'required'
        ]);


        $id_client = explode("_", $validated['client'])[1];
        $client = null;
        foreach ($this->clients as $c) {
            if ($c->A1_CGC == $id_client) {
                $client = $c;
            }
        }

        $service = explode("_", $validated['service']);
        $price = null;

        foreach ($this->prices as $p) {
            if (trim($p->DA1_CODPRO) == $service[0] && trim($p->DA0_CODTAB) == $service[1]) {
                $price = $p;
            }
        }

        if ($client && $price) {
            array_push($this->results, [
                'cc' => $this->cc,
                'weekref' => $this->weekref,
                'day' => $validated['day'],
                'client' => [
                    'cgc' => trim($client->A1_CGC),
                    'name' => trim($client->A1_NOME)
                ],
                'service' => $price,
                'qty' => $validated['qty']
            ]);

            $this->save();
        }

        $this->getTotal();
    }

    public function getTotal()
    {
        foreach ($this->results as $r) {
            $this->total_qty += (float) $r['qty'];
        }
    }

    public function mount()
    {
        $this->cc = session('preview')['cc'];
        $this->weekref = session('preview')['week_ref'];

        $this->clients = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where('A1_CC', $this->cc)
            ->get();

        $this->prices = DB::connection('mysql_dump')
            ->table('TABELAPRECO')
            ->where('DA0_CC', $this->cc)
            ->get();

        $result = Option::where([
            'cc' => $this->cc,
            'week_ref' => $this->weekref,
            'option_name' => 'left',
        ])->first();

        if ($result) {
            $this->results = unserialize($result->option_value);
            $this->getTotal();
        }

        preg_match('/(\d{2})+(\d{2})+(\d{2})/', $this->weekref, $matches);

        $this->month = $matches[1] . '/' . $matches[3];
        $this->lastOfMonth = (int) Carbon::parse("{$matches[2]}-{$matches[1]}-01")->lastOfMonth()->format('d');
    }

    public function reset_form()
    {
        $this->day = '';
        $this->client = '';
        $this->service = '';
        $this->qty = 0;

        $this->total_qty = 0;
        $this->clientSelected = null;

        $this->filtered_prices = [];
    }

    public function save()
    {
        if (!$this->edit) return;

        foreach ($this->results as $r) {
            $this->total_qty += (float) $r['qty'];
        }

        // serializa o conteúdo
        $content = serialize($this->results);

        // cria ou faz update da invoicing para aquela prévia
        Option::updateOrCreate(
            [
                'cc' => $this->cc,
                'week_ref' => $this->weekref,
                'option_name' => 'left',
            ],
            [
                'option_value' => $content,
                'total' => 0,
            ]
        );

        $this->reset_form();

        session()->flash('message', [
            'type' => 'success',
            'message' => 'Salvo com sucesso.',
        ]);
    }

    public function render()
    {
        return view('livewire.category.category-left');
    }
}
