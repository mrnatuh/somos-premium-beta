<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UserCreate extends Component
{
    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|email|unique:users')]
    public string $email = '';

    #[Rule('required|min:5')]
    public string $password = '';

    #[Rule('required|min:5')]
    public string $access = '';

    public string $cc = '';

    public function generatePassword(): void
    {
        $lowercase = range('a', 'z');
        $uppercase = range('A', 'Z');
        $digits = range(0, 9);
        $special = ['!', '@', '#', '$', '%', '^', '*'];
        $chars = array_merge($lowercase, $uppercase, $digits, $special);
        $length = 12;
        do {
            $password = array();

            for ($i = 0; $i <= $length; $i++) {
                $int = rand(0, count($chars) - 1);
                $password[] = $chars[$int];
            }
        } while (empty(array_intersect($special, $password)));

        $this->setPassword(implode('', $password));
    }

    private function setPassword($value): void
    {
        $this->password = $value;
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'access' => $this->access,
            'cc' => $this->cc,
        ]);

        return redirect()->to('/profiles');
    }

    public function render()
    {
        return view('livewire.user.user-create');
    }
}
