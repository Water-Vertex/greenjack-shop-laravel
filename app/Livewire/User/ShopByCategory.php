<?php

namespace App\Livewire\User;

use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

#[Layout('components.shop-layout')]
class ShopByCategory extends Component
{
    use WithPagination;

    // View settings
    public $viewType = 'grid';
    public $perPage = 12;
    public $sortBy = 'latest';

    // Filters
    public $selectedCategories = [];
    public $minPrice = 0;
    public $maxPrice = 10000;
    public $priceRange = [0, 10000];
    public $search = '';
    public $slug = null;
    public $categoryName = '';

    // Price range from database
    public $globalMinPrice = 0;
    public $globalMaxPrice = 10000;

    // Track which product is being added to cart
    public $addingToCart = null;

    protected $queryString = [
        'viewType' => ['except' => 'grid'],
        'sortBy' => ['except' => 'latest'],
        'perPage' => ['except' => 12],
        'selectedCategories' => ['except' => []],
        'priceRange' => ['except' => [0, 10000]],
        'search' => ['except' => ''],
    ];

    public function mount($slug = null)
    {
        try {
            $this->slug = $slug;

            // Category lookup
            if ($slug) {
                try {
                    $category = Category::where('slug', $slug)->first();
                    if ($category) {
                        $this->categoryName = $category->name ?? '';
                        $this->selectedCategories = [$category->id];
                    } else {
                        // Category not found — redirect to shop
                        return redirect()->route('shop')->with('error', 'Category not found.');
                    }
                } catch (\Throwable $e) {
                    $this->logError('mount:category', $e);
                }
            }

            // Global price range
            try {
                $this->globalMinPrice = (float) (Product::min('price') ?? 0);
                $this->globalMaxPrice = (float) (Product::max('price') ?? 10000);
                if ($this->globalMaxPrice <= 0) {
                    $this->globalMaxPrice = 10000;
                }
            } catch (\Throwable $e) {
                $this->logError('mount:priceRange', $e);
                $this->globalMinPrice = 0;
                $this->globalMaxPrice = 10000;
            }

            $this->minPrice = $this->globalMinPrice;
            $this->maxPrice = $this->globalMaxPrice;
            $this->priceRange = [$this->globalMinPrice, $this->globalMaxPrice];

        } catch (\Throwable $e) {
            $this->logError('mount', $e);
            return redirect()->route('shop')->with('error', 'Could not load category. Please try again.');
        }
    }

    public function toggleWishlist($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $product = Product::find($productId);
            if (!$product) {
                session()->flash('error', 'Product not found.');
                return;
            }

            $sessionId = Session::getId();
            $userId = Auth::id();

            $query = Wishlist::where('product_id', $productId);
            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            $wishlistItem = $query->first();

            if ($wishlistItem) {
                $wishlistItem->delete();
                session()->flash('success', 'Removed from wishlist.');
                $this->dispatch('wishlist-removed', productId: $productId);
            } else {
                Wishlist::create([
                    'customer_id' => $userId,
                    'product_id' => $productId,
                    'session_id' => $userId ? null : $sessionId,
                ]);
                session()->flash('success', 'Added to wishlist!');
                $this->dispatch('wishlist-added', productId: $productId);
            }

            $this->dispatch('wishlist-updated');

        } catch (\Throwable $e) {
            $this->logError('toggleWishlist', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not update wishlist. Please try again.');
        }
    }

    public function updatedPriceRange($value)
    {
        try {
            if (is_array($value) && count($value) >= 2) {
                $this->minPrice = (float) $value[0];
                $this->maxPrice = (float) $value[1];
            }
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->logError('updatedPriceRange', $e);
        }
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleView($view)
    {
        try {
            $this->viewType = in_array($view, ['grid', 'list']) ? $view : 'grid';
        } catch (\Throwable $e) {
            $this->logError('toggleView', $e);
        }
    }

    public function clearFilters()
    {
        try {
            if ($this->slug) {
                $category = Category::where('slug', $this->slug)->first();
                if ($category) {
                    $this->selectedCategories = [$category->id];
                } else {
                    $this->selectedCategories = [];
                }
            } else {
                $this->selectedCategories = [];
            }

            $this->priceRange = [$this->globalMinPrice, $this->globalMaxPrice];
            $this->minPrice = $this->globalMinPrice;
            $this->maxPrice = $this->globalMaxPrice;
            $this->search = '';
            $this->sortBy = 'latest';
            $this->resetPage();

            session()->flash('success', 'All filters cleared.');

        } catch (\Throwable $e) {
            $this->logError('clearFilters', $e);
            session()->flash('error', 'Could not clear filters. Please refresh the page.');
        }
    }

    public function addToCart($productId, $quantity = 1)
    {
        try {
            $this->addingToCart = $productId;

            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                $this->addingToCart = null;
                return;
            }

            $quantity = max(1, (int) $quantity);

            $product = Product::find($productId);
            if (!$product) {
                session()->flash('error', 'Product not found.');
                $this->addingToCart = null;
                return;
            }

            // Variable product → redirect to details
            if ($product->has_variations) {
                $this->addingToCart = null;
                return redirect()->route('shop.details', $product->slug);
            }

            if (($product->stock ?? 0) < $quantity) {
                session()->flash('error', 'Product not available in requested quantity.');
                $this->addingToCart = null;
                return;
            }

            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            $price = ($product->discounted_price && $product->discounted_price > 0)
                ? $product->discounted_price
                : $product->price;

            if (isset($cart[$productId])) {
                $newQuantity = ($cart[$productId]['quantity'] ?? 0) + $quantity;
                if ($newQuantity > $product->stock) {
                    session()->flash('error', 'Cannot add more than available stock.');
                    $this->addingToCart = null;
                    return;
                }
                $cart[$productId]['quantity'] = $newQuantity;
                session()->flash('success', 'Cart updated successfully!');
            } else {
                $cart[$productId] = [
                    'id' => $productId,
                    'product_id' => $productId,
                    'variation_id' => null,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'price' => $price,
                    'quantity' => $quantity,
                    'stock' => $product->stock,
                    'sku' => $product->sku,
                    'variation_attributes' => '',
                ];
                session()->flash('success', 'Product added to cart!');
            }

            Session::put('cart', $cart);
            $this->dispatch('cart-updated');
            $this->addingToCart = null;

        } catch (\Throwable $e) {
            $this->logError('addToCart', $e, ['product_id' => $productId]);
            session()->flash('error', 'Failed to add product to cart.');
            $this->addingToCart = null;
        }
    }

    public function getProductsProperty()
    {
        try {
            $query = Product::with(['category', 'variations'])
                ->where('is_active', true);

            // Apply category filter based on slug or selected categories
            if ($this->slug) {
                try {
                    $category = Category::where('slug', $this->slug)->first();
                    if ($category) {
                        $query->where('category_id', $category->id);
                    }
                } catch (\Throwable $e) {
                    $this->logError('getProductsProperty:categoryLookup', $e);
                }
            }

            if (!empty($this->selectedCategories) && is_array($this->selectedCategories)) {
                $query->whereIn('category_id', $this->selectedCategories);
            }

            // Search filter
            if (!empty($this->search)) {
                $search = trim($this->search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%');
                });
            }

            // Price range filter
            $min = (float) ($this->minPrice ?? 0);
            $max = (float) ($this->maxPrice ?? 10000);
            if ($max > 0) {
                $query->whereBetween('price', [$min, $max]);
            }

            // Sorting
            switch ($this->sortBy) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('views', 'desc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            return $query->paginate($this->perPage);

        } catch (\Throwable $e) {
            $this->logError('getProductsProperty', $e);
            // Empty paginator — page crash nahi hoga
            return Product::where('id', 0)->paginate($this->perPage);
        }
    }

    public function render()
    {
        // Categories with counts
        $categories = collect();
        try {
            $categories = Category::withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])->orderBy('position', 'asc')->get();
        } catch (\Throwable $e) {
            $this->logError('render:categories', $e);
        }

        // Current category
        $currentCategory = null;
        try {
            if ($this->slug) {
                $currentCategory = Category::where('slug', $this->slug)->first();
            }
        } catch (\Throwable $e) {
            $this->logError('render:currentCategory', $e);
        }

        // Products (safe)
        $products = null;
        try {
            $products = $this->products;
        } catch (\Throwable $e) {
            $this->logError('render:products', $e);
            $products = Product::where('id', 0)->paginate($this->perPage);
        }

        return view('livewire.user.shop-by-category', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
        ]);
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[ShopByCategory::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => Auth::id(),
                'url' => request()->fullUrl(),
                'slug' => $this->slug,
            ], $extra));
        } catch (\Throwable $inner) {
            // silent
        }
    }
}
