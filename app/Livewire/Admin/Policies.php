<?php

namespace App\Livewire\Admin;

use App\Models\Policy;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Policies extends Component
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
    public $slug = '';
    public $description = '';  
    public $meta_title = '';
    public $meta_description = '';
    public $page_schema = '';
    public $is_repairing_policy = false; // Added this

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
        'is_repairing_policy' => 'boolean',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'showForm', 'formType', 'pageId', 'title', 'slug', 'description', 
            'meta_title', 'meta_description', 'page_schema', 'is_repairing_policy',
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
        $policy = Policy::findOrFail($pageId);
        
        $this->formType = 'edit';
        $this->pageId = $policy->id;
        $this->title = $policy->title;
        $this->description = $policy->description;
        $this->meta_title = $policy->meta_title;
        $this->meta_description = $policy->meta_description;
        $this->page_schema = $policy->page_schema;
        $this->is_repairing_policy = $policy->is_repairing_policy ?? false;
        $this->showForm = true;
        $this->analyzeSeo();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'page_schema' => $this->page_schema,
            'is_repairing_policy' => $this->is_repairing_policy ?? false,
        ];

        if ($this->formType === 'create') {
            Policy::create($data);
            session()->flash('success', 'Policy created successfully.');
        } else {
            $policy = Policy::findOrFail($this->pageId);
            $policy->update($data);
            session()->flash('success', 'Policy updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($pageId)
    {
        $policy = Policy::findOrFail($pageId);
        $policy->delete();
        session()->flash('success', 'Policy deleted successfully.');
    }

    public function render()
    {
        $policies = Policy::when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.policies', [
            'policies' => $policies
        ]);
    }
}