<?php

namespace App\Livewire\Category;

use Livewire\Component;

class CategoryHeader extends Component
{
    public $title = '';

    public $notifications = [
        [
            'id' => 1,
            'message' => 'Hello world'
        ]
    ];

    public function render()
    {
        return view('livewire.category.category-header');
    }
}
