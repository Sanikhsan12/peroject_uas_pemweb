<?php

namespace App\Livewire;

use Livewire\Component;

class LoginComponent extends Component
{
    public $email = '';
    public $password = '';

    public function render()
    {
        return view('livewire.login-component');
    }

    public function proses()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ],[
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 6 karakter'
        ]);
    }
}
