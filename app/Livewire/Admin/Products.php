<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.admin-layout')]
class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $showForm = false;
    public $formType = 'create';
    public $productId = null;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $image;
    public $existingImage = null;

    public $price = 0;
    public $discounted_price = null;
    public $stock = 0;
    public $is_active = true;

    public $upc = '';
    public $sku = '';
    public $asin = '';
    public $manufacturer = '';
    public $moq = null;

    public $meta_description = '';
    public $meta_title = '';
    public $meta_keywords = '';
    public $meta_tags = '';
    public $page_schemas = '';

    public $category_id = '';
    public $sub_category_id = '';
    public $focus_keyword = '';
    public $type = '';
    public $deal_of_the_day = '';

    public $galleryImages = [];
    public $existingGalleryImages = [];

    public $seoScore = 0;
    public $seoMetrics = [];
    public $subCategories = [];

    public $excelFile = null;
    public $showImportModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $this->productId,
            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'galleryImages.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',

            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0|lte:price',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',

            'upc' => 'nullable|string|max:50',
            'sku' => 'nullable|string|max:100',
            'asin' => 'nullable|string|max:50',
            'manufacturer' => 'nullable|string|max:255',
            'moq' => 'nullable|integer|min:1',

            'meta_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_tags' => 'nullable|string',
            'page_schemas' => 'nullable|string',

            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'focus_keyword' => 'nullable|string|max:255',
            'type' => 'nullable|in:featured,best_seller',
        ];
    }

    protected function importRules()
    {
        return [
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ];
    }

    public function mount()
    {
        $this->subCategories = [];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();

        $this->showForm = true;
        $this->formType = 'create';
        $this->is_active = true;
    }

    public function resetForm()
    {
        $this->resetExcept([
            'search',
            'sortField',
            'sortDirection',
            'perPage',
        ]);

        $this->showForm = false;
        $this->formType = 'create';
        $this->productId = null;

        $this->price = 0;
        $this->stock = 0;
        $this->is_active = true;

        $this->subCategories = [];
        $this->galleryImages = [];
        $this->existingGalleryImages = [];

        $this->seoScore = 0;
        $this->seoMetrics = [];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedName()
    {
        if ($this->formType === 'create') {
            $this->slug = Str::slug($this->name);
        }

        $this->analyzeSeo();
    }

    public function updatedMetaTitle()
    {
        $this->analyzeSeo();
    }

    public function updatedMetaDescription()
    {
        $this->analyzeSeo();
    }

    public function updatedFocusKeyword()
    {
        $this->analyzeSeo();
    }

    public function updatedDescription()
    {
        $this->analyzeSeo();
    }

    public function updatedCategoryId($value)
    {
        $this->sub_category_id = '';

        $this->subCategories = $value
            ? SubCategory::where('category_id', $value)->orderBy('name')->get()->toArray()
            : [];

        $this->analyzeSeo();
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $slug = $this->slug ?: Str::slug($this->name);

            $imagePath = $this->existingImage;

            if ($this->image) {
                if ($this->existingImage) {
                    Storage::disk('public')->delete($this->existingImage);
                }

                $imagePath = $this->image->store('products', 'public');
            }

            $data = [
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'image' => $imagePath,

                'price' => $this->price,
                'discounted_price' => $this->discounted_price ?: null,
                'stock' => $this->stock,
                'is_active' => $this->is_active ? 1 : 0,

                'upc' => $this->upc,
                'sku' => $this->sku,
                'asin' => $this->asin,
                'manufacturer' => $this->manufacturer,
                'moq' => $this->moq ?: null,

                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'meta_tags' => $this->meta_tags,
                'page_schemas' => $this->page_schemas,

                'category_id' => $this->category_id ?: null,
                'sub_category_id' => $this->sub_category_id ?: null,
                'focus_keyword' => $this->focus_keyword,
                'type' => $this->type ?: null,
                'deal_of_the_day' => $this->deal_of_the_day ?: null,
            ];

            if ($this->formType === 'edit' && $this->productId) {
                $product = Product::findOrFail($this->productId);
                $product->update($data);

                $message = 'Product updated successfully.';
            } else {
                $product = Product::create($data);

                $message = 'Product created successfully.';
            }

            if (!empty($this->galleryImages)) {
    foreach ($this->galleryImages as $galleryImage) {
        $path = $galleryImage->store('products/gallery', 'public');

        ProductImage::create([
            'product_id' => $product->id,
            'images' => json_encode([$path]),  // ✅ Store as JSON array
        ]);
    }
}

            DB::commit();

            session()->flash('success', $message);

            $this->resetForm();
            $this->resetPage();

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Product save error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            session()->flash('error', 'Product save failed: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::with('productImages')->findOrFail($id);

        $this->resetForm();

        $this->productId = $product->id;
        $this->formType = 'edit';
        $this->showForm = true;

        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->existingImage = $product->image;

        $this->price = $product->price;
        $this->discounted_price = $product->discounted_price;
        $this->stock = $product->stock;
        $this->is_active = (bool) $product->is_active;

        $this->upc = $product->upc;
        $this->sku = $product->sku;
        $this->asin = $product->asin;
        $this->manufacturer = $product->manufacturer;
        $this->moq = $product->moq;

        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        $this->meta_keywords = $product->meta_keywords;
        $this->meta_tags = $product->meta_tags;
        $this->page_schemas = $product->page_schemas;

        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->focus_keyword = $product->focus_keyword;
        $this->type = $product->type;
        $this->deal_of_the_day = $product->deal_of_the_day;

        if ($product->category_id) {
            $this->subCategories = SubCategory::where('category_id', $product->category_id)
                ->orderBy('name')
                ->get()
                ->toArray();
        }

        $this->existingGalleryImages = $product->productImages->map(function ($image) {
            return [
                'id' => $image->id,
                'images' => $image->images,
            ];
        })->toArray();

        $this->analyzeSeo();
    }

    public function delete($id)
    {
        $product = Product::with('productImages')->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            foreach ($product->productImages as $image) {
                if ($image->images) {
                    Storage::disk('public')->delete($image->images);
                }

                $image->delete();
            }

            $product->delete();

            DB::commit();

            session()->flash('success', 'Product deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            session()->flash('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'is_active' => !$product->is_active,
        ]);

        session()->flash('success', 'Status updated successfully.');
    }

    public function removeNewImage($index)
    {
        unset($this->galleryImages[$index]);

        $this->galleryImages = array_values($this->galleryImages);
    }

    public function removeExistingImage($id, $path)
    {
        $image = ProductImage::find($id);

        if ($image) {
            if ($image->images) {
                Storage::disk('public')->delete($image->images);
            }

            $image->delete();
        }

        $this->existingGalleryImages = collect($this->existingGalleryImages)
            ->reject(fn ($item) => $item['id'] == $id)
            ->values()
            ->toArray();

        session()->flash('success', 'Gallery image removed successfully.');
    }

    public function analyzeSeo()
    {
        $score = 0;

        $metrics = [
            [
                'name' => 'Product Name',
                'status' => filled($this->name),
                'message' => filled($this->name) ? 'Good' : 'Missing',
            ],
            [
                'name' => 'Meta Title',
                'status' => filled($this->meta_title),
                'message' => filled($this->meta_title) ? 'Good' : 'Missing',
            ],
            [
                'name' => 'Meta Description',
                'status' => filled($this->meta_description),
                'message' => filled($this->meta_description) ? 'Good' : 'Missing',
            ],
            [
                'name' => 'Focus Keyword',
                'status' => filled($this->focus_keyword),
                'message' => filled($this->focus_keyword) ? 'Good' : 'Missing',
            ],
            [
                'name' => 'Description',
                'status' => filled($this->description),
                'message' => filled($this->description) ? 'Good' : 'Missing',
            ],
        ];

        foreach ($metrics as $metric) {
            if ($metric['status']) {
                $score += 20;
            }
        }

        $this->seoScore = $score;
        $this->seoMetrics = $metrics;
    }

    public function openImportModal()
    {
        $this->reset(['excelFile']);
        $this->resetErrorBag();
        $this->resetValidation();

        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->reset(['excelFile']);

        $this->showImportModal = false;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function importProducts()
    {
        $this->validate($this->importRules());

        try {
            $import = new ProductsImport();

            Excel::import($import, $this->excelFile);

            $count = method_exists($import, 'getSuccessCount')
                ? $import->getSuccessCount()
                : 0;

            session()->flash('success', "Successfully imported {$count} products!");

            $this->closeImportModal();
            $this->resetPage();

        } catch (\Throwable $e) {
            Log::error('Import error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'name',
            'description',
            'stock',
            'discounted_price',
            'price',
            'category_id',
            'is_active',
            'upc',
            'asin',
            'sku',
            'manufacturer',
            'moq',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'meta_tags',
            'page_schemas',
            'sub_category_id',
            'focus_keyword',
            'type',
        ];

        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);

            fputcsv($file, [
                'Premium Laptop',
                'High-performance laptop',
                '50',
                '999.99',
                '1299.99',
                '1',
                '1',
                '123456789012',
                'B08N5WRWNW',
                'LAP-001',
                'Dell',
                '5',
                'Premium Laptop - Best Deal',
                'Buy premium laptop online',
                'laptop, premium',
                'premium-laptop',
                '{"@context":"https://schema.org"}',
                '1',
                'premium laptop',
                'featured',
            ]);

            fclose($file);
        }, 'product_import_template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        $products = Product::with(['category', 'subCategory'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%')
                        ->orWhere('upc', 'like', '%' . $this->search . '%')
                        ->orWhere('manufacturer', 'like', '%' . $this->search . '%')
                        ->orWhere('meta_title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.products', compact('products', 'categories'));
    }
}
