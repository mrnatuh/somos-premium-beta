<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        return view('livewire.user.user-index', [
            'users' => User::search('name', $this->search ?? '')
                ->paginate(10),
            'total' => User::count(),
        ]);
    }
}
