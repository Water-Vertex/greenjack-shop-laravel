<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Problem;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]

class BrandProblems extends Component
{
    public $selectedCategory = null;
    public $selectedBrand = null;

    public $categories = [];
    public $brands = [];
    public $problems = [];

    public $selectedProblems = [];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->brands = collect();
        $this->problems = collect();
    }

    public function updatedSelectedCategory($value)
    {
        $this->selectedBrand = null;
        $this->selectedProblems = [];
        $this->problems = collect();

        if ($value) {
            $this->brands = Brand::whereHas('categories', function ($query) use ($value) {
                $query->where('categories.id', $value);
            })->orderBy('name')->get();
        } else {
            $this->brands = collect();
        }
    }

    public function updatedSelectedBrand($value)
    {

        $this->selectedProblems = [];

        if ($value) {
            $this->problems = Problem::where(['brand_id' => $value, 'category_id' => $this->selectedCategory])
            ->select('id', 'name', 'description', 'price', 'discounted_price')
            ->orderBy('name')
            ->get();

        } else {
            $this->problems = collect();

        }
    }

    public function save()
    {
        $this->validate([
            'selectedCategory' => 'required',
            'selectedBrand' => 'required',
            'selectedProblems' => 'required|array|min:1',
        ]);

        // Here you can save selected problem IDs wherever you need.
        // Example: session flash only for now.

        session()->flash('success', count($this->selectedProblems) . ' problems selected successfully.');
    }

    public function render()
    {
        return view('livewire.admin.brand-problems');
    }
}
