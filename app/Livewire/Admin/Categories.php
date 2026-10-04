<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Categories extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Form properties
    public $showForm = false;
    public $formType = 'create'; // 'create' or 'edit'
    public $categoryId = null;
    public $name = '';
    public $description = '';
    public $parent_id = '';
    public $image;
    public $existingImage = null; // Added for existing image
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $meta_tags = '';
    public $page_schemas = '';
    public $position = 0;
    public $is_active = true;
    public $is_repairing_category = false;

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
        'parent_id' => 'nullable|exists:categories,id',
        'image' => 'nullable|image|max:2048',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'meta_tags' => 'nullable|string',
        'page_schemas' => 'nullable|string',
        'position' => 'integer|min:0',
        'is_active' => 'boolean',
        'is_repairing_category' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'Category name is required',
        'name.max' => 'Category name cannot exceed 255 characters',
        'parent_id.exists' => 'Selected parent category does not exist',
        'image.max' => 'Image size cannot exceed 2MB',
        'image.image' => 'File must be an image',
        'position.integer' => 'Position must be a valid number',
        'position.min' => 'Position cannot be negative'
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'categoryId', 'name', 'description',
            'parent_id', 'image', 'existingImage', 'meta_title', 'meta_description',
            'meta_keywords', 'meta_tags', 'page_schemas', 'position', 'is_active',
            'is_repairing_category', 'seoScore', 'seoMetrics'
        ]);
        $this->resetValidation();
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

    public function edit($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $this->formType = 'edit';
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->existingImage = $category->image; // Set existing image
        $this->meta_title = $category->meta_title;
        $this->meta_description = $category->meta_description;
        $this->meta_keywords = $category->meta_keywords;
        $this->meta_tags = $category->meta_tags;
        $this->page_schemas = $category->page_schemas;
        $this->position = $category->position;
        $this->is_active = $category->is_active;
        $this->is_repairing_category = $category->is_repairing_category ?? false;
        $this->showForm = true;
        $this->loadParentCategories();
        $this->analyzeSeo();
    }

    public function save()
    {
        $this->validate();

        // Check if slug already exists (excluding current category in edit mode)
        $slug = Str::slug($this->name);
        $existingCategory = Category::where('slug', $slug)
            ->when($this->formType === 'edit', function($query) {
                return $query->where('id', '!=', $this->categoryId);
            })
            ->first();

        if ($existingCategory) {
            $this->addError('name', 'A category with this name already exists. Please use a different name.');
            return;
        }

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id ?: null,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_tags' => $this->meta_tags,
            'page_schemas' => $this->page_schemas,
            'position' => $this->position,
            'is_active' => $this->is_active,
            'is_repairing_category' => $this->is_repairing_category,
        ];

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists in edit mode
            if ($this->formType === 'edit' && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('categories', 'public');
            $data['image'] = $imagePath;
        } elseif ($this->formType === 'edit' && $this->existingImage) {
            // Keep existing image
            $data['image'] = $this->existingImage;
        }

        if ($this->formType === 'create') {
            Category::create($data);
            session()->flash('success', 'Category created successfully.');
        } else {
            $category = Category::findOrFail($this->categoryId);
            $category->update($data);
            session()->flash('success', 'Category updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // Check if category has subcategories
        if ($category->children()->exists()) {
            session()->flash('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
            return;
        }

        // Check if category has products
        if ($category->products()->exists()) {
            session()->flash('error', 'Cannot delete category with associated products.');
            return;
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        session()->flash('success', 'Category deleted successfully.');
    }

    public function toggleStatus($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);

        session()->flash('success', 'Category status updated successfully.');
    }

    public function removeImage()
    {
        if ($this->existingImage) {
            Storage::disk('public')->delete($this->existingImage);
            $this->existingImage = null;

            // If editing, update the database
            if ($this->formType === 'edit' && $this->categoryId) {
                $category = Category::find($this->categoryId);
                if ($category) {
                    $category->update(['image' => null]);
                }
            }

            session()->flash('success', 'Image removed successfully.');
        }
    }

    public function render()
    {
        $categories = Category::with(['parent', 'children'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_keywords', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $parentCategories = $this->deferredLoaded ?
            Category::whereNull('parent_id')
                ->where('is_active', true)
                ->where(function($query) {
                    // Exclude current category in edit mode
                    if ($this->formType === 'edit' && $this->categoryId) {
                        $query->where('id', '!=', $this->categoryId);
                    }
                })
                ->orderBy('name')
                ->get() : collect([]);

        return view('livewire.admin.categories', [
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ]);
    }
}
