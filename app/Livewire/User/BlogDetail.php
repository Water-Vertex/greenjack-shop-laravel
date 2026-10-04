<?php

namespace App\Livewire\User;

use App\Models\Blog;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.user-layout')]
class BlogDetail extends Component
{
    public $slug;
    public Blog $blog;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->blog = Blog::where('slug', $slug)
                          ->where('is_active', true)
                          ->firstOrFail();
    }

    public function render()
    {
        // Related blogs (same site, excluding current)
        $relatedBlogs = Blog::where('is_active', true)
            ->where('id', '!=', $this->blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.user.blog-detail', [
            'blog'         => $this->blog,
            'relatedBlogs' => $relatedBlogs,
        ]);
    }

}
