<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Login extends Component
{
    public $id_login;
    public $password;

    protected $rules = [
        'id_login' => 'required',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        // Data 3 Akun yang diminta (Bisa dipindah ke Database nanti)
        $users = [
            ['id' => 'admin_pstore', 'pass' => 'pstore123'],
            ['id' => 'staff_pstore', 'pass' => 'staff789'],
            ['id' => 'owner_pstore', 'pass' => 'pstorewin'],
        ];

        $authSuccess = false;
        foreach ($users as $user) {
            if ($this->id_login === $user['id'] && $this->password === $user['pass']) {
                $authSuccess = true;
                break;
            }
        }

        if ($authSuccess) {
            // Set session login manual karena tidak pakai database User default
            Session::put('is_logged_in', true);
            Session::put('user_id', $this->id_login);
            Session::put('last_activity', time());

            return redirect()->to('/');
        }

        session()->flash('error', 'ID Login atau Password salah!');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}