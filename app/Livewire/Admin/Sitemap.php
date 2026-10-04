<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Sitemap extends Component
{
    public string $type = 'html';
    public $lastUpdated;

    public function mount(): void
    {
        $this->lastUpdated = now()->format('Y-m-d H:i:s');
    }

    public function staticPages(): array
    {
        return [
            ['url' => url('/'),           'title' => 'Home',    'priority' => '1.0', 'changefreq' => 'daily',   'lastmod' => now()->toDateString()],
            ['url' => url('/contact-us'), 'title' => 'Contact', 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString()],
            ['url' => url('/blogs'),      'title' => 'Blogs',   'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
            ['url' => url('/shop'),       'title' => 'Shop',    'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
        ];
    }

    public function dynamicPages(): array
    {
        return [
            'blogs' => Blog::latest()->get()->map(fn($i) => [
                'url'        => url('/blog/' . $i->slug),
                'title'      => $i->title ?? 'Untitled',
                'priority'   => '0.8',
                'changefreq' => 'monthly',
                'lastmod'    => optional($i->updated_at)->toDateString(),
            ])->toArray(),

            'categories' => Category::latest()->get()->map(fn($i) => [
                'url'        => url('/category/' . $i->slug),
                'title'      => $i->name ?? 'Untitled',
                'priority'   => '0.6',
                'changefreq' => 'weekly',
                'lastmod'    => optional($i->updated_at)->toDateString(),
            ])->toArray(),

            'products' => Product::latest()->get()->map(fn($i) => [
                'url'        => url('/product/' . $i->slug),
                'title'      => $i->name ?? 'Untitled',
                'priority'   => '0.7',
                'changefreq' => 'weekly',
                'lastmod'    => optional($i->updated_at)->toDateString(),
            ])->toArray(),
        ];
    }

    public function allUrls(): array
    {
        $urls = $this->staticPages();
        foreach ($this->dynamicPages() as $items) {
            foreach ($items as $item) {
                $urls[] = $item;
            }
        }
        return $urls;
    }

    public function render()
    {
        $dynamicPages = $this->dynamicPages();
        return view('livewire.admin.sitemap', [
            'staticPages'  => $this->staticPages(),
            'dynamicPages' => $dynamicPages,
            'urls'         => $this->allUrls(),
            'totalBlogs'      => count($dynamicPages['blogs']),
            'totalCategories' => count($dynamicPages['categories']),
            'totalProducts'   => count($dynamicPages['products']),
            'totalUrls'       => count($this->allUrls()),
        ]);
    }
}
