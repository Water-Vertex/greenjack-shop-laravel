<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RepairCustomerLogout extends Component
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

        return redirect('/');
    }

    public function render()
    {
        // Show a loading message or redirecting message
        return view('livewire.auth.repair-customer-logout');
    }
}
