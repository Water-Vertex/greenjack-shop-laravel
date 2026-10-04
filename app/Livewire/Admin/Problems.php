<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Problem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Problems extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Selection properties
    public $selectedCategory = null;
    public $selectedBrand = null;
    public $selectedModel = null;

    // Data collections
    public $categories = [];
    public $brands = [];
    public $models = [];

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $problemId = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $discounted_price = '';
    public $status = 'active';
    public $is_featured = '';
    public $is_recommended = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];

    protected $rules = [
        'selectedCategory' => 'required',
        'selectedBrand' => 'required',
        'selectedModel' => 'required',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'discounted_price' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'selectedCategory.required' => 'Please select a category.',
        'selectedBrand.required' => 'Please select a brand.',
        'selectedModel.required' => 'Please select a model.',
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->brands = collect();
        $this->models = collect();
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'problemId', 'name', 'slug', 'description',
            'price', 'discounted_price', 'status', 'selectedCategory', 'selectedBrand', 'selectedModel'
        ]);
        $this->brands = collect();
        $this->models = collect();
        $this->resetErrorBag();
    }

    public function updatedSelectedCategory($value)
    {
        if ($value) {
            $this->brands = Brand::whereHas('categories', function($query) use ($value) {
                $query->where('categories.id', $value);
            })->orderBy('name')->get();

            $this->selectedBrand = null;
            $this->selectedModel = null;
            $this->models = collect();
        } else {
            $this->brands = collect();
            $this->models = collect();
        }
    }

    public function updatedSelectedBrand($value)
    {
        if ($value) {
            $this->models = BrandModel::where('brand_id', $value)
                ->orderBy('name')
                ->get();
            $this->selectedModel = null;
        } else {
            $this->models = collect();
        }
    }

    public function updatedName($value)
    {
        if ($this->formType === 'create' && !$this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function edit($problemId)
    {
        $problem = Problem::with('model.brand.categories')->findOrFail($problemId);

        $this->resetForm();
        $this->formType = 'edit';
        $this->problemId = $problem->id;
        $this->name = $problem->name;
        $this->slug = $problem->slug;
        $this->description = $problem->description;
        $this->price = $problem->price;
        $this->discounted_price = $problem->discounted_price;
        $this->selectedCategory = $problem->category_id;
        $this->selectedBrand = $problem->brand_id;
        $this->selectedModel = $problem->brand_model_id;
        $this->is_featured = $problem->is_featured;
        $this->is_recommended = $problem->is_recommended;

        if ($problem->model) {
            $this->selectedBrand = $problem->model->brand_id;

            if ($problem->model->brand && $problem->model->brand->categories->first()) {
                $this->selectedCategory = $problem->model->brand->categories->first()->id;

                $this->brands = Brand::whereHas('categories', function($query) use ($problem) {
                    $query->where('categories.id', $problem->model->brand->categories->first()->id);
                })->orderBy('name')->get();

                $this->models = BrandModel::where('brand_id', $this->selectedBrand)
                    ->orderBy('name')
                    ->get();
            }
        }

        $this->showForm = true;
        $this->rules['slug'] = 'required|string|max:255|unique:problems,slug,' . $problemId;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'brand_model_id' => $this->selectedModel,
            'brand_id' => $this->selectedBrand,
            'category_id' => $this->selectedCategory,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price ?: null,
            'discounted_price' => $this->discounted_price ?: null,
            'is_featured' => $this->is_featured ?: null,
            'is_recommended' => $this->is_recommended ?: null,
        ];

        if ($this->formType === 'create') {
            Problem::create($data);
            session()->flash('success', 'Problem created successfully.');
        } else {
            $problem = Problem::findOrFail($this->problemId);
            $problem->update($data);
            session()->flash('success', 'Problem updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($problemId)
    {
        $problem = Problem::findOrFail($problemId);
        $problem->delete();
        session()->flash('success', 'Problem deleted successfully.');
    }

    public function toggleStatus($problemId)
    {
        $problem = Problem::findOrFail($problemId);
        $problem->status = $problem->status === 'active' ? 'inactive' : 'active';
        $problem->save();
        session()->flash('success', 'Problem status updated successfully.');
    }

    public function render()
    {
        $problems = Problem::with('model')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.problems', [
            'problems' => $problems
        ]);
    }
}
