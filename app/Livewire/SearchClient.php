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

    #[On('clear-client-search')]
    public function clearClientSearch()
    {
        $this->q = '';
        $this->clients = [];
    }

    public function getClients()
    {
        $this->validate();

        $this->clients = DB::connection('mysql_dump')
            ->table('CLIENTES')
            ->where('A1_NOME', 'LIKE', "%{$this->q}%")
            ->orWhere('A1_COD', 'LIKE', "%{$this->q}%")
            ->orWhere('A1_CC', 'LIKE', "%{$this->q}%")
            ->get();
    }
}
