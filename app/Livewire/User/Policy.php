<?php

namespace App\Livewire\User;

use App\Models\Policy as PagePolicy;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.shop-layout')]
class Policy extends Component
{
    public $slug;
    public PagePolicy $policy;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->policy = PagePolicy::where('slug', $slug)
                          ->firstOrFail();
    }

    public function render()
    {
      
        return view('livewire.user.policy', [
            'policy'         => $this->policy,
        ]);
    }

}
