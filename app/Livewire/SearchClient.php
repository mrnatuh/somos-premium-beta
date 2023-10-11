<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SearchClient extends Component
{
    public $clients = [];

    #[Rule('required|string|min:3')]
    public $q = '';

    public function render()
    {
        return view('livewire.search-client');
    }

    public function sendData(string $client_id)
    {
        $this->q = '';
        $this->clients = [];

        return $this->dispatch('search-add-client', client_id: $client_id);
    }

    public function getClients()
    {
        $cc = session('preview')['cc'];

        $this->validate();

        $this->clients = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where([['A1_CC', '=', $cc], ['A1_NOME', 'LIKE', "%{$this->q}%"]])
            ->orWhere([['A1_CC', '=', $cc], ['A1_COD', 'LIKE', "%{$this->q}%"]])
            ->orWhere([['A1_CC', '=', $cc], ['A1_CGC', 'LIKE', "%{$this->q}%"]])
            ->get();
    }
}
