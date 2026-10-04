<?php

namespace App\Livewire\User;

use App\Models\Blog as BlogModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.user-layout')]
class Blogs extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $blogs = BlogModel::query()
            ->where('is_active', true)
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('livewire.user.blogs', [
            'blogs' => $blogs,
        ]);
    }
}
