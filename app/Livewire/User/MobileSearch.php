<?php

namespace App\Livewire\User;

use Livewire\Component;

class MobileSearch extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $showResults = false;
    public $isLoading = false;

    public function updatedSearchTerm($value)
    {
        $this->performSearch();
    }

    public function performSearch()
    {
        if (strlen($this->searchTerm) < 2) {
            $this->searchResults = [];
            $this->showResults = false;
            return;
        }

        $this->isLoading = true;

        $this->searchResults = \App\Models\Product::where(function($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchTerm . '%');
            })
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'compare_price' => $product->compare_price,
                    'image' => $product->image,
                ];
            })
            ->toArray();

        $this->showResults = count($this->searchResults) > 0;
        $this->isLoading = false;
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->searchResults = [];
        $this->showResults = false;
    }

    public function navigateToSearch()
    {
        if (strlen($this->searchTerm) >= 2) {
            return redirect()->route('shop', ['search' => $this->searchTerm]);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.user.mobile-search');
    }
}
