<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sign Up')]
class Register extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';
    #[Validate('required|email|unique:users')]
    public string $email = '';
    #[Validate('required|confirmed|min:8')]
    public string $password = '';
    #[Validate('required')]
    public string $password_confirmation = '';


    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        session()->regenerate();

        dd('Registration successful! Redirecting to dashboard...');
    }


    public function render()
    {
        return view('livewire.auth.register');
    }
}
