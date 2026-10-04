<?php

namespace App\Livewire\Admin;

use App\Models\Blog as BlogModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Blog extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $isEdit = false;
    public $blogId = null;

    // Form fields
    public $name = '';
    public $slug = '';
    public $description = '';
    public $image = null;
    public $oldImage = null;
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $meta_tags = '';
    public $page_schemas = '';
    public $is_active = true;
    public $uploading = false;

    // Filters
    public $search = '';
    public $perPage = 10;

    // SEO Analysis properties
    public $seoScore = 0;
    public $seoMetrics = [];

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'slug' => 'required|regex:/^[a-z0-9-]+$/|unique:blogs,slug',
        'description' => 'required|min:20',
        'image' => 'nullable|image|max:2048',
        'meta_title' => 'nullable|max:60',
        'meta_description' => 'nullable|max:160',
        'meta_keywords' => 'nullable|max:255',
        'meta_tags' => 'nullable|max:255',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Blog title is required',
        'slug.unique' => 'This slug already exists',
        'slug.regex' => 'Slug can only contain letters, numbers and hyphens',
        'description.min' => 'Content must be at least 20 characters',
        'image.max' => 'Image must be less than 2MB',
    ];

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
        $this->analyzeSeo();
    }

    public function updatedSlug($value)
    {
        $this->slug = Str::slug($value);
        $this->analyzeSeo();
    }

    public function updatedDescription()
    {
        $this->analyzeSeo();
    }

    public function updatedImage()
    {
        $this->uploading = true;
        $this->validate([
            'image' => 'image|max:2048'
        ]);
        $this->uploading = false;
    }

    public function analyzeSeo()
    {
        $this->seoMetrics = [];

        // Analyze Blog Title
        $titleLength = strlen($this->name);
        $this->seoMetrics[] = [
            'name' => 'Blog Title',
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

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
        $this->analyzeSeo();
    }

    public function edit($id)
    {
        $blog = BlogModel::findOrFail($id);
        $this->blogId = $blog->id;
        $this->name = $blog->name;
        $this->slug = $blog->slug;
        $this->description = $blog->description;
        $this->oldImage = $blog->image;
        $this->meta_title = $blog->meta_title;
        $this->meta_description = $blog->meta_description;
        $this->meta_keywords = $blog->meta_keywords;
        $this->meta_tags = $blog->meta_tags;
        $this->page_schemas = $blog->page_schemas;
        $this->is_active = $blog->is_active;
        $this->isEdit = true;
        $this->showModal = true;
        $this->analyzeSeo();
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEdit) {
            $rules['slug'] = 'required|regex:/^[a-z0-9-]+$/|unique:blogs,slug,' . $this->blogId;
        }
        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'meta_tags' => $this->meta_tags,
            'page_schemas' => $this->page_schemas,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $path = $this->image->store('blogs', 'public');
            $data['image'] = $path;

            if ($this->isEdit && $this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
        } elseif ($this->isEdit) {
            $data['image'] = $this->oldImage;
        }

        if ($this->isEdit) {
            BlogModel::find($this->blogId)->update($data);
            session()->flash('message', 'Blog updated successfully!');
        } else {
            BlogModel::create($data);
            session()->flash('message', 'Blog created successfully!');
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $blog = BlogModel::findOrFail($id);
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        session()->flash('message', 'Blog deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $blog = BlogModel::findOrFail($id);
        $blog->is_active = !$blog->is_active;
        $blog->save();
        session()->flash('message', 'Status changed successfully!');
    }

    public function resetForm()
    {
        $this->reset(['showModal', 'isEdit', 'blogId', 'name', 'slug', 'description',
                      'image', 'oldImage', 'meta_title', 'meta_description',
                      'meta_keywords', 'meta_tags', 'page_schemas']);
        $this->is_active = true;
        $this->seoScore = 0;
        $this->seoMetrics = [];
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    public function render()
    {
        $blogs = BlogModel::query()
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%')
                  ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                  ->orWhere('meta_description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.blog', [
            'blogs' => $blogs
        ]);
    }
}
