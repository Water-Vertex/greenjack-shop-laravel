<?php

namespace App\Livewire\Admin;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Banners extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    public $showForm = false;
    public $formType = 'create';
    public $bannerId = null;

    public $title = '';
    public $sub_title = '';
    public $description = '';
    public $url = '';
    public $visibility = '';
    public $image;
    public $existingImage = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    protected function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'visibility' => 'nullable|string|max:255',
            'image' => $this->formType === 'create'
                ? 'nullable|image|max:2048'
                : 'nullable|image|max:2048',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'showForm',
            'formType',
            'bannerId',
            'title',
            'sub_title',
            'description',
            'url',
            'image',
            'existingImage',
            'visibility'
        ]);

        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->formType = 'create';
        $this->showForm = true;
    }

    public function edit($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);

        $this->formType = 'edit';
        $this->bannerId = $banner->id;
        $this->title = $banner->title;
        $this->sub_title = $banner->sub_title;
        $this->description = $banner->description;
        $this->url = $banner->url;
        $this->existingImage = $banner->image;
        $this->image = null;
        $this->visibility = $banner->visibility;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'url' => $this->url,
            'visibility' => $this->visibility,
        ];

        if ($this->image) {
            if ($this->formType === 'edit' && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }

            $data['image'] = $this->image->store('banners', 'public');
        }

        if ($this->formType === 'create') {
            Banner::create($data);
            session()->flash('success', 'Banner created successfully.');
        } else {
            $banner = Banner::findOrFail($this->bannerId);
            $banner->update($data);
            session()->flash('success', 'Banner updated successfully.');
        }

        $this->resetForm();
    }

    public function delete($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        session()->flash('success', 'Banner deleted successfully.');
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

    public function render()
    {
        $banners = Banner::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('sub_title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('url', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.banners', [
            'banners' => $banners,
        ]);
    }
}
