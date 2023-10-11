<?php

namespace App\Livewire\User;

use App\Models\LinkUser;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UserEdit extends Component
{
    public User $user;

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:5')]
    public string $access = '';

    public string $password = '';

    public string $cc = '';

    public string $q = '';

    public $results = [];

    public $links = [];

    public $not_links = [];

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

    public function setPassword(string $value)
    {
        $this->password = $value;
    }

    public function update()
    {
        $exists = User::where('email', $this->email)->first();

        if ($exists->id !== $this->user->id) {
            return redirect('/profiles/edit/' . $this->user->id)->with('error', 'E-mail já cadastrado!');
        }

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->cc = $this->cc;
        $this->user->access = $this->access;

        $this->user->save();

        return redirect('/profiles/edit/' . $this->user->id)->with('success', 'Alterado com sucesso!');
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:4',
        ]);

        $this->user->password = $this->password;
        $this->user->save();

        return redirect('/profiles/edit/' . $this->user->id)->with('success', 'Alterado com sucesso!');
    }

    public function findUsers(string $type): void
    {
        $this->results = [];

        if ($this->q == '') {
            $this->results = [];
            return;
        }

        if ($type == 'supervisor') {
            $this->results = User::where([
                ['access', '=', 'user'],
                ['name', 'LIKE', "%{$this->q}%"]
            ])->whereNotIn('id', $this->not_links)->get();
        } else if ($type === 'coordinator') {
            $this->results = User::where([
                ['access', '=', $type],
                ['name', 'LIKE', "%{$this->q}%"]
            ])->whereNotIn('id', $this->not_links)->get();
        }
    }

    public function linkUser($id)
    {
        LinkUser::updateOrCreate([
            'user_id' => $this->user->id,
            'parent_id' => $id,
        ], [
            'user_id' => $this->user->id,
            'parent_id' => $id,
        ]);

        return redirect('/profiles/edit/' . $this->user->id)->with('success', 'Alterado com sucesso!');
    }

    public function deleteParentUser($parent_id)
    {
        LinkUser::where([
            'user_id' => $this->user->id,
            'parent_id' => $parent_id,
        ])->first()->delete();

        return redirect('/profiles/edit/' . $this->user->id)->with('success', 'Alterado com sucesso!');
    }

    public function mount($user)
    {
        $this->user = $user;

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        // $this->password = $this->user->password;
        $this->access = $this->user->access;
        $this->cc = $this->user->cc ?? '';

        $tmp_ids = LinkUser::all();
        $parent_ids = [];

        foreach ($tmp_ids as $id) {
            if ($id->user_id == $this->user->id) {
                array_push($parent_ids, $id->parent_id);
            }

            if (!isset($this->not_links[$id->parent_id])) {
                array_push($this->not_links, $id->parent_id);
            }
        }

        $this->links = User::whereIn('id', $parent_ids)->get();
    }

    public function render()
    {
        return view('livewire.user.user-edit');
    }
}
