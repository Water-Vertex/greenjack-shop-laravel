<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.admin-layout')]
#[Title('General Settings')]
class Settings extends Component
{
    use WithFileUploads;

    public $settings;
    public $favicon;
    public $main_logo;
    public $footer_logo;
    public $facebook;
    public $twitter;
    public $linkedin;
    public $instagram;
    public $pintrest;
    public $youtube;
    public $email;
    public $about;
    public $site_title;
    public $meta_title;
    public $meta_tags;
    public $meta_desc;
    public $meta_keywords;
    public $head_tags;
    public $body_tags;
    public $phone;
    public $address;

    protected $rules = [
        'favicon' => 'nullable|image|max:1024',
        'main_logo' => 'nullable|image|max:2048',
        'footer_logo' => 'nullable|image|max:2048',
        'facebook' => 'nullable|url',
        'twitter' => 'nullable|url',
        'linkedin' => 'nullable|url',
        'instagram' => 'nullable|url',
        'pintrest' => 'nullable|url',
        'youtube' => 'nullable|url',
        'email' => 'nullable|email',
        'about' => 'nullable|string',
        'site_title' => 'nullable|string|max:255',
        'meta_title' => 'nullable|string|max:255',
        'meta_tags' => 'nullable|string',
        'meta_desc' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'head_tags' => 'nullable|string',
        'body_tags' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ];

    public function mount()
    {
        $this->settings = Setting::first();
        $this->loadSettings();
    }

    public function loadSettings()
    {
        if ($this->settings) {
            $this->facebook = $this->settings->facebook;
            $this->twitter = $this->settings->twitter;
            $this->linkedin = $this->settings->linkedin;
            $this->instagram = $this->settings->instagram;
            $this->pintrest = $this->settings->pintrest;
            $this->youtube = $this->settings->youtube;
            $this->email = $this->settings->email;
            $this->about = $this->settings->about;
            $this->site_title = $this->settings->site_title;
            $this->meta_title = $this->settings->meta_title;
            $this->meta_tags = $this->settings->meta_tags;
            $this->meta_desc = $this->settings->meta_desc;
            $this->meta_keywords = $this->settings->meta_keywords;
            $this->head_tags = $this->settings->head_tags;
            $this->body_tags = $this->settings->body_tags;
            $this->phone = $this->settings->phone;
            $this->address = $this->settings->address;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
            'pintrest' => $this->pintrest,
            'youtube' => $this->youtube,
            'email' => $this->email,
            'about' => $this->about,
            'site_title' => $this->site_title,
            'meta_title' => $this->meta_title,
            'meta_tags' => $this->meta_tags,
            'meta_desc' => $this->meta_desc,
            'meta_keywords' => $this->meta_keywords,
            'head_tags' => $this->head_tags,
            'body_tags' => $this->body_tags,
            'phone' => $this->phone,
            'address' => $this->address,
        ];

        // Handle file uploads
        if ($this->favicon) {
            if ($this->settings && $this->settings->favicon) {
                Storage::disk('public')->delete($this->settings->favicon);
            }
            $data['favicon'] = $this->favicon->store('settings', 'public');
        }

        if ($this->main_logo) {
            if ($this->settings && $this->settings->main_logo) {
                Storage::disk('public')->delete($this->settings->main_logo);
            }
            $data['main_logo'] = $this->main_logo->store('settings', 'public');
        }

        if ($this->footer_logo) {
            if ($this->settings && $this->settings->footer_logo) {
                Storage::disk('public')->delete($this->settings->footer_logo);
            }
            $data['footer_logo'] = $this->footer_logo->store('settings', 'public');
        }

        if ($this->settings) {
            $this->settings->update($data);
            session()->flash('success', 'Settings updated successfully.');
        } else {
            Setting::create($data);
            session()->flash('success', 'Settings created successfully.');
            $this->settings = Setting::first();
        }

        $this->loadSettings();
    }

    public function removeImage($type)
    {
        if ($this->settings) {
            $field = match($type) {
                'favicon' => 'favicon',
                'main_logo' => 'main_logo',
                'footer_logo' => 'footer_logo',
            };

            if ($this->settings->$field) {
                Storage::disk('public')->delete($this->settings->$field);
                $this->settings->update([$field => null]);
                session()->flash('success', ucfirst(str_replace('_', ' ', $type)) . ' removed successfully.');
            }
        }

        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}