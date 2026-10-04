<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class SubCategories extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Form properties
    public $showForm = false;
    public $formType = 'create'; // 'create' or 'edit'
    public $subcategoryId = null;
    public $name = '';
    public $description = '';
    public $category_id = '';
    public $image;
    public $existingImage = null; // Added for existing image
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $meta_tags = '';
    public $page_schemas = '';
    public $position = 0;
    public $is_active = true;

    // SEO Analysis properties
    public $seoScore = 0;
    public $seoMetrics = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'nullable',
        'image' => 'nullable|image|max:2048',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'meta_tags' => 'nullable|string',
        'page_schemas' => 'nullable|string',
        'position' => 'integer|min:0',
        'is_active' => 'boolean'
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'subcategoryId', 'name', 'description',
            'category_id', 'image', 'existingImage', 'meta_title', 'meta_description',
            'meta_keywords', 'meta_tags', 'page_schemas', 'position', 'is_active',
            'seoScore', 'seoMetrics'
        ]);
    }

    public function analyzeSeo()
    {
        $this->seoMetrics = [];

        // Analyze Category Name
        $nameLength = strlen($this->name);
        $this->seoMetrics[] = [
            'name' => 'Category Name',
            'status' => $nameLength >= 3 && $nameLength <= 60,
            'message' => "{$nameLength}/60 chars",
            'weight' => 20
        ];

        // Analyze Meta Title
        $metaTitleLength = strlen($this->meta_title);
        $this->seoMetrics[] = [
            'name' => 'Meta Title',
            'status' => $metaTitleLength >= 10 && $metaTitleLength <= 60,
            'message' => "{$metaTitleLength}/60 chars",
            'weight' => 20
        ];

        // Analyze Meta Description
        $metaDescLength = strlen($this->meta_description);
        $this->seoMetrics[] = [
            'name' => 'Meta Description',
            'status' => $metaDescLength >= 50 && $metaDescLength <= 160,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 20
        ];

        // Analyze Description Content
        $textContent = strip_tags($this->description);
        $wordCount = str_word_count($textContent);
        $this->seoMetrics[] = [
            'name' => 'Description Length',
            'status' => $wordCount >= 50,
            'message' => "{$wordCount}/50+ words",
            'weight' => 15
        ];

        // Analyze Keywords
        $keywordsCount = !empty($this->meta_keywords) ? count(array_filter(array_map('trim', explode(',', $this->meta_keywords)))) : 0;
        $this->seoMetrics[] = [
            'name' => 'Keywords',
            'status' => $keywordsCount >= 3 && $keywordsCount <= 10,
            'message' => "{$keywordsCount} keywords",
            'weight' => 15
        ];

        // Analyze Meta Tags
        $hasMetaTags = !empty($this->meta_tags);
        $this->seoMetrics[] = [
            'name' => 'Meta Tags',
            'status' => $hasMetaTags,
            'message' => $hasMetaTags ? 'Added' : 'Missing',
            'weight' => 10
        ];

        // Calculate overall score
        $this->calculateSeoScore();
    }

    private function calculateSeoScore()
    {
        $totalWeight = 0;
        $achievedWeight = 0;

        foreach ($this->seoMetrics as $metric) {
            $totalWeight += $metric['weight'];
            if ($metric['status']) {
                $achievedWeight += $metric['weight'];
            }
        }

        $this->seoScore = $totalWeight > 0 ?
            round(($achievedWeight / $totalWeight) * 100) : 0;
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

    public $deferredLoaded = false;

    public function loadParentCategories()
    {
        if (!$this->deferredLoaded) {
            $this->deferredLoaded = true;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
        $this->loadParentCategories();
        $this->analyzeSeo();
    }

    public function edit($subcategoryId)
    {
        $subcategory = SubCategory::findOrFail($subcategoryId);

        $this->formType = 'edit';
        $this->subcategoryId = $subcategory->id;
        $this->name = $subcategory->name;
        $this->description = $subcategory->description;
        $this->category_id = $subcategory->category_id;
        $this->existingImage = $subcategory->image; // Set existing image
        $this->meta_title = $subcategory->meta_title;
        $this->meta_description = $subcategory->meta_description;
        $this->meta_keywords = $subcategory->meta_keywords;
        $this->meta_tags = $subcategory->meta_tags;
        $this->page_schemas = $subcategory->page_schemas;
        $this->position = $subcategory->position;
        $this->is_active = $subcategory->is_active;
        $this->showForm = true;
        $this->loadParentCategories();
        $this->analyzeSeo();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'category_id' => $this->category_id ?: null,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_tags' => $this->meta_tags,
            'page_schemas' => $this->page_schemas,
            'position' => $this->position,
            'is_active' => $this->is_active,
        ];

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists
            if ($this->formType === 'edit' && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('subcategories', 'public');
            $data['image'] = $imagePath;
        } elseif ($this->formType === 'edit' && $this->existingImage) {
            // Keep existing image
            $data['image'] = $this->existingImage;
        }

        if ($this->formType === 'create') {
            SubCategory::create($data);
            session()->flash('success', 'Sub Category created successfully.');
        } else {
            $subcategory = SubCategory::findOrFail($this->subcategoryId);
            $subcategory->update($data);
            session()->flash('success', 'Sub Category updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($subcategoryId)
    {
        $subcategory = SubCategory::findOrFail($subcategoryId);

        // Check if sub-category has products
        if ($subcategory->products()->exists()) {
            session()->flash('error', 'Cannot delete sub-category with associated products.');
            return;
        }

        // Delete image if exists
        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
        }

        $subcategory->delete();
        session()->flash('success', 'Sub-category deleted successfully.');
    }

    public function toggleStatus($subcategoryId)
    {
        $subcategory = SubCategory::findOrFail($subcategoryId);
        $subcategory->update(['is_active' => !$subcategory->is_active]);

        session()->flash('success', 'Sub-category status updated successfully.');
    }

    // Add method to remove image
    public function removeImage()
    {
        if ($this->existingImage) {
            Storage::disk('public')->delete($this->existingImage);
            $this->existingImage = null;

            // If editing, update the database
            if ($this->formType === 'edit' && $this->subcategoryId) {
                $subcategory = SubCategory::find($this->subcategoryId);
                if ($subcategory) {
                    $subcategory->update(['image' => null]);
                }
            }

            session()->flash('success', 'Image removed successfully.');
        }
    }

    public function render()
    {
        $subcategories = SubCategory::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_keywords', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = $this->deferredLoaded ?
            Category::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('name')
                ->get() : collect([]);

        return view('livewire.admin.sub-categories', [
            'subcategories' => $subcategories,
            'categories' => $categories
        ]);
    }
}
