<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Category;
use App\Models\Series as SeriesList;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.admin-layout')]
class Series extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $seriesId = null;

    // Filter properties for models
    public $selectedCategoryId = null;
    public $selectedBrandId = null;
    public $availableModels = [];
    public $selectedModels = [];
    public $availableBrands = []; // Add this for filtered brands

    // Series properties
    public $name = '';
    public $slug = '';
    public $brand_model_id = null;

    // Add this to track if models are being loaded
    public $loadingModels = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];

    protected $rules = [
        'selectedCategoryId' => 'required|exists:categories,id',
        'selectedBrandId' => 'required|exists:brands,id',
        'name' => 'required|string|max:255',
        'brand_model_id' => 'nullable|exists:brand_models,id',
        'selectedModels' => 'required|array|min:1',
        'selectedModels.*' => 'exists:brand_models,id',
    ];

    protected $messages = [
        'selectedCategoryId.required' => 'Please select a category.',
        'selectedBrandId.required' => 'Please select a brand.',
        'selectedModels.required' => 'Please select at least one model.',
        'selectedModels.min' => 'Please select at least one model.',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'seriesId', 'selectedCategoryId', 'selectedBrandId',
            'name', 'slug', 'brand_model_id', 'selectedModels', 'availableModels', 'loadingModels', 'availableBrands'
        ]);
        $this->resetErrorBag();
    }

    public function updatedName($value)
    {
        if ($this->formType === 'create' && !$this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSelectedCategoryId()
    {
        // Reset brand and models when category changes
        $this->selectedBrandId = null;
        $this->availableModels = [];
        $this->selectedModels = [];
        $this->brand_model_id = null;

        // Load brands that have models in this category
        $this->loadAvailableBrands();
    }

    public function updatedSelectedBrandId()
    {
        $this->loadModels();
    }

    public function loadAvailableBrands()
    {
        if ($this->selectedCategoryId) {
            // Get distinct brand IDs that have models in this category
            $brandIds = BrandModel::where('category_id', $this->selectedCategoryId)
                ->distinct()
                ->pluck('brand_id');

            // Load only those brands
            $this->availableBrands = Brand::whereIn('id', $brandIds)
                ->orderBy('name')
                ->get();
        } else {
            $this->availableBrands = [];
        }
    }

    public function loadModels()
    {
        if ($this->selectedCategoryId && $this->selectedBrandId) {
            $this->loadingModels = true;

            $this->availableModels = BrandModel::where('category_id', $this->selectedCategoryId)
                ->where('brand_id', $this->selectedBrandId)
                ->orderBy('name')
                ->get();

            $this->loadingModels = false;
        } else {
            $this->availableModels = [];
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

    public function edit($seriesId)
    {
        $series = SeriesList::with('seriesModels')->findOrFail($seriesId);

        $this->formType = 'edit';
        $this->seriesId = $series->id;
        $this->selectedCategoryId = $series->category_id;

        // Load available brands first
        $this->loadAvailableBrands();

        $this->selectedBrandId = $series->brand_id;
        $this->name = $series->name;
        $this->slug = $series->slug;
        $this->brand_model_id = $series->brand_model_id;

        // Load available models based on selected category and brand
        $this->loadModels();

        // Set selected models
        $this->selectedModels = $series->seriesModels->pluck('id')->toArray();

        $this->showForm = true;
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . $e->getMessage());
            return;
        }

        $data = [
            'category_id' => $this->selectedCategoryId,
            'brand_id' => $this->selectedBrandId,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand_model_id' => $this->brand_model_id,
        ];

        if ($this->formType === 'create') {
            $series = SeriesList::create($data);

            // Attach selected models to the series
            if (!empty($this->selectedModels)) {
                $series->seriesModels()->attach($this->selectedModels);
            }

            session()->flash('success', 'Series created successfully.');
        } else {
            $series = SeriesList::findOrFail($this->seriesId);
            $series->update($data);

            // Sync selected models
            $series->seriesModels()->sync($this->selectedModels);

            session()->flash('success', 'Series updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($seriesId)
    {
        $series = SeriesList::findOrFail($seriesId);

        // Detach all models first
        $series->seriesModels()->detach();
        $series->delete();

        session()->flash('success', 'Series deleted successfully.');
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        // For the main table view, get all brands (unfiltered)
        $allBrands = Brand::orderBy('name')->get();

        $seriesList = SeriesList::with(['category', 'brand', 'seriesModels'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.series', [
            'seriesList' => $seriesList,
            'categories' => $categories,
            'allBrands' => $allBrands, // For display in the table
        ]);
    }
}
