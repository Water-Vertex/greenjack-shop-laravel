<?php

namespace App\Livewire\User;
;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.user-layout')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.user.home');
    }
}
