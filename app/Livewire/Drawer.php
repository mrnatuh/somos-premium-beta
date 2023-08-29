<?php

namespace App\Livewire;

use Livewire\Component;

class Drawer extends Component
{
    public $id = 'drawer-example';

    public $show = false;

    public function render()
    {
        return view('livewire.drawer');
    }
}
