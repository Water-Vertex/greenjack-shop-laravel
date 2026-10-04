<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.guest-layout')]
class Logout extends Component
{
    public function mount()
    {
        // Call logout automatically when component loads
        $this->logout();
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/login');
    }

    public function render()
    {
        // Show a loading message or redirecting message
        return view('livewire.auth.logout');
    }
}