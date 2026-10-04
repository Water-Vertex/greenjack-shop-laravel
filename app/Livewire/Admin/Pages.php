<?php

namespace App\Livewire\Admin;

use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Pages extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Form properties
    public $showForm = false;
    public $formType = 'create';
    public $pageId = null;
    public $title = '';
    public $page_category = '';
    public $slug = '';
    public $description = '';
    public $short_description = '';
    public $content_right = '';
    public $content_left = '';
    public $featured_image;              // for new upload
    public $existing_featured_image;     // for DB value

    public $section_image_left;
    public $existing_section_image_left;

    public $section_image_right;
    public $existing_section_image_right;
    public $meta_title = '';
    public $meta_description = '';
    public $page_schema = '';

    // SEO Analysis properties
    public $seoScore = 0;
    public $seoMetrics = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'page_schema' => 'nullable|string',
        'featured_image' => 'nullable|image|max:2048',
        'section_image_left' => 'nullable|image|max:2048',
        'section_image_right' => 'nullable|image|max:2048',
        'content_right' => 'nullable|string',
        'content_left' => 'nullable|string',
        'short_description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'pageId', 'page_category', 'title', 'slug',  'description', 'short_description', 'content_right', 'content_left', 'section_image_right', 'section_image_left', 'featured_image',
            'meta_title', 'meta_description', 'page_schema',
            'seoScore', 'seoMetrics'
        ]);
        $this->resetErrorBag();
    }

    public function analyzeSeo()
    {
        $this->seoMetrics = [];
        
        // Analyze Page Title
        $titleLength = strlen($this->title);
        $this->seoMetrics[] = [
            'name' => 'Page Title',
            'status' => $titleLength >= 10 && $titleLength <= 60,
            'message' => "{$titleLength}/60 chars",
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
            'status' => $metaDescLength >= 120 && $metaDescLength <= 160,
            'message' => "{$metaDescLength}/160 chars",
            'weight' => 15
        ];

        // Analyze Content
        $textContent = strip_tags($this->description);
        $wordCount = str_word_count($textContent);
        $this->seoMetrics[] = [
            'name' => 'Content Length',
            'status' => $wordCount >= 300,
            'message' => "{$wordCount}/300+ words",
            'weight' => 20
        ];

        // Analyze Headings
        $h1Count = substr_count(strtolower($this->description), '<h1');
        $h2Count = substr_count(strtolower($this->description), '<h2');
        $this->seoMetrics[] = [
            'name' => 'Heading Structure',
            'status' => $h1Count === 1 && $h2Count >= 1,
            'message' => "H1: {$h1Count}, H2: {$h2Count}",
            'weight' => 15
        ];

        // Analyze Slug
        $slugLength = strlen($this->slug);
        $this->seoMetrics[] = [
            'name' => 'URL Slug',
            'status' => $slugLength >= 3 && $slugLength <= 50,
            'message' => "{$slugLength}/50 chars",
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

    public function updatedTitle($value)
    {
        if ($this->formType === 'create' && !$this->slug) {
            $this->slug = Str::slug($value);
        }
        $this->analyzeSeo();
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
        $this->analyzeSeo();
    }

    public function edit($pageId)
    {
        $page = Page::findOrFail($pageId);
        
        $this->formType = 'edit';
        $this->pageId = $page->id;
        $this->page_category = $page->page_category;
        $this->title = $page->title;
        $this->slug = Str::slug($page->title);
        $this->description = $page->description;
        $this->short_description = $page->short_description;
        $this->content_right = $page->content_right;
        $this->content_left = $page->content_left;
        $this->existing_featured_image = $page->featured_image;
        $this->existing_section_image_left = $page->section_image_left;
        $this->existing_section_image_right = $page->section_image_right;
        $this->meta_title = $page->meta_title;
        $this->meta_description = $page->meta_description;
        $this->page_schema = $page->page_schema;
        $this->showForm = true;
        $this->analyzeSeo();

       
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'page_category' => $this->page_category,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'content_right' => $this->content_right,
            'content_left' => $this->content_left,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'schema_markup' => $this->page_schema,
        ];

        if ($this->featured_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            // delete old
            if ($this->existing_featured_image) {
                Storage::disk('public')->delete($this->existing_featured_image);
            }

            $data['featured_image'] = $this->featured_image->store('pages', 'public');
        }

        if ($this->section_image_left instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
             // delete old
             if ($this->existing_section_image_left) {
                Storage::disk('public')->delete($this->existing_section_image_left);
            }
            $imagePath = $this->section_image_left->store('pages', 'public');
            $data['section_image_left'] = $imagePath;
        }

        if ($this->section_image_right instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            // delete old
            if ($this->existing_section_image_right) {
                Storage::disk('public')->delete($this->existing_section_image_right);
            }   
            $imagePath = $this->section_image_right->store('pages', 'public');
            $data['section_image_right'] = $imagePath;
        }


        if ($this->formType === 'create') {
            Page::create($data);
            session()->flash('success', 'Page created successfully.');
        } else {
            $page = Page::findOrFail($this->pageId);
            // Delete old featured image if new one is uploaded
            if ($this->featured_image && $page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            if ($this->section_image_left && $page->section_image_left) {
                Storage::disk('public')->delete($page->section_image_left);
            }
            if ($this->section_image_right && $page->section_image_right) {
                Storage::disk('public')->delete($page->section_image_right);
            }
            $page->update($data);
            session()->flash('success', 'Page updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($pageId)
    {
        $page = Page::findOrFail($pageId);
        if ($this->featured_image && $page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            if ($this->section_image_left && $page->section_image_left) {
                Storage::disk('public')->delete($page->section_image_left);
            }
            if ($this->section_image_right && $page->section_image_right) {
                Storage::disk('public')->delete($page->section_image_right);
            }
        $page->delete();
        session()->flash('success', 'Page deleted successfully.');
    }

    public function render()
    {
        $pages = Page::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.pages', [
            'pages' => $pages
        ]);
    }
}