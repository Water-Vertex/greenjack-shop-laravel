<?php

namespace App\Livewire\User;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.shop-layout')]
class Categories extends Component
{
    public $categories ;
    public $categoryCounts = [];

    public function mount()
    {
        // Get all categories with product counts
        $this->categories = Category::withCount('products')->get();

        // Prepare category counts for display
        $this->categoryCounts = $this->categories->mapWithKeys(function ($category) {
            return [$category->id => $category->products_count];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.user.categories', [
            'categories' => $this->categories,
            'categoryCounts' => $this->categoryCounts
        ]);
    }
}
