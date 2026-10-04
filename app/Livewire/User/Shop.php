<?php

namespace App\Livewire\User;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

#[Layout('components.shop-layout')]
class Shop extends Component
{
    use WithPagination;

    public $addingToCart = null;
    public $search = '';
    public $selectedCategory = '';
    public $sortBy = 'newest';
    public $perPage = 12;
    public $subscribeEmail = '';

    // ✅ Global error bag — frontend pe dikhane ke liye
    public $pageError = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        try {
            $this->search = '';
            $this->selectedCategory = '';
            $this->sortBy = 'newest';
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->logError('clearFilters', $e);
            session()->flash('error', 'Could not clear filters. Please refresh the page.');
        }
    }

    public function addToCart($productId, $quantity = 1)
    {
        try {
            $this->addingToCart = $productId;

            // Validate input
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

            // ✅ Variable product → redirect to details page
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
                    'variation_attributes' => ''
                ];
                session()->flash('success', 'Product added to cart!');
            }

            Session::put('cart', $cart);
            $this->dispatch('cart-updated');
            $this->addingToCart = null;

        } catch (\Throwable $e) {
            $this->logError('addToCart', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not add product to cart. Please try again.');
            $this->addingToCart = null;
        }
    }

    public function getProductsProperty()
    {
        try {
            $query = Product::with('category')->where('is_active', true);

            if (!empty($this->search)) {
                $search = trim($this->search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('short_description', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%');
                });
            }

            if (!empty($this->selectedCategory)) {
                $query->where('category_id', $this->selectedCategory);
            }

            switch ($this->sortBy) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'best_selling':
                    $query->orderBy('sales_count', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'series_desc':
                default:
                    // REGEXP_SUBSTR MySQL 8+ specific hai — agar fail ho to fallback
                    try {
                        $query->orderByRaw("CAST(REGEXP_SUBSTR(name, '[0-9]+') AS UNSIGNED) DESC")
                              ->orderBy('created_at', 'desc');
                    } catch (\Throwable $e) {
                        // Fallback: simple order
                        $query->orderBy('created_at', 'desc');
                    }
                    break;
            }

            return $query->paginate($this->perPage);

        } catch (\Throwable $e) {
            $this->logError('getProductsProperty', $e);
            // Return empty paginator so page still renders
            return Product::where('id', 0)->paginate($this->perPage);
        }
    }

    public function getBestSellerProducts()
    {
        try {
            return Product::with(['category', 'variations'])
                ->where('type', 'best_seller')
                ->limit(12)
                ->get();
        } catch (\Throwable $e) {
            $this->logError('getBestSellerProducts', $e);
            return collect(); // empty collection
        }
    }

    public function getFeaturedProducts()
    {
        try {
            return Product::with(['category', 'variations'])
                ->where('is_active', true)
                ->where('type', 'featured')
                ->get();
        } catch (\Throwable $e) {
            $this->logError('getFeaturedProducts', $e);
            return collect();
        }
    }

    public function getBannersProperty()
    {
        try {
            return Banner::where('visibility', 'home_main_banner')->get();
        } catch (\Throwable $e) {
            $this->logError('getBannersProperty', $e);
            return collect();
        }
    }

    public function getMainSideBannerProperty()
    {
        try {
            return Banner::where('visibility', 'home_main_side_banner')->latest()->first();
        } catch (\Throwable $e) {
            $this->logError('getMainSideBannerProperty', $e);
            return null;
        }
    }

    public function getSideBanners()
    {
        try {
            return Banner::where('visibility', 'home_side_banner')->get();
        } catch (\Throwable $e) {
            $this->logError('getSideBanners', $e);
            return collect();
        }
    }

    public function getDealProduct()
    {
        try {
            return Product::with(['category', 'variations'])
                ->where('deal_of_the_day', 1)
                ->latest()
                ->first();
        } catch (\Throwable $e) {
            $this->logError('getDealProduct', $e);
            return null;
        }
    }

    public function getBrandsProperty()
    {
        try {
            return Brand::get();
        } catch (\Throwable $e) {
            $this->logError('getBrandsProperty', $e);
            return collect();
        }
    }

    public function getSpecialProducts()
    {
        try {
            return Product::with(['category', 'variations'])
                ->where('is_active', true)
                ->whereIn('type', ['best_seller', 'featured'])
                ->get();
        } catch (\Throwable $e) {
            $this->logError('getSpecialProducts', $e);
            return collect();
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
                    'session_id' => $userId ? null : $sessionId
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

    public function subscribe()
    {
        try {
            $validator = Validator::make(
                ['email' => $this->subscribeEmail],
                ['email' => 'required|email|max:255']
            );

            if ($validator->fails()) {
                $this->addError('subscribeEmail', $validator->errors()->first('email'));
                return;
            }

            $email = strtolower(trim($this->subscribeEmail));

            $existing = Subscriber::where('email', $email)->first();

            if ($existing) {
                if ($existing->unsubscribed_at || !$existing->is_active) {
                    $existing->update([
                        'is_active'       => true,
                        'unsubscribed_at' => null,
                        'verified_at'     => now(),
                        'ip_address'      => request()->ip(),
                    ]);
                    session()->flash('subscribe_success', 'Welcome back! You\'re subscribed again.');
                } else {
                    session()->flash('subscribe_info', 'You\'re already subscribed!');
                }

                $this->subscribeEmail = '';
                return;
            }

            Subscriber::create([
                'email'       => $email,
                'is_active'   => true,
                'verified_at' => now(),
                'ip_address'  => request()->ip(),
            ]);

            $this->subscribeEmail = '';
            session()->flash('subscribe_success', 'Thanks for subscribing! Check your inbox for offers.');

        } catch (\Throwable $e) {
            $this->logError('subscribe', $e, ['email' => $this->subscribeEmail]);
            session()->flash('subscribe_error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        // ✅ Har section ko alag try-catch me wrap karo
        // taake ek fail ho to baaki page render ho jaye

        $categories = $this->safe(fn() => Category::orderBy('position', 'asc')->get(), collect(), 'categories');
        $products = $this->safe(fn() => $this->products, null, 'products');
        $banners = $this->safe(fn() => $this->banners, collect(), 'banners');
        $side_banners = $this->safe(fn() => $this->getSideBanners(), collect(), 'side_banners');
        $main_side_banner = $this->safe(fn() => $this->getMainSideBannerProperty(), null, 'main_side_banner');
        $brands = $this->safe(fn() => $this->brands, collect(), 'brands');
        $deal_of_the_day = $this->safe(fn() => $this->getDealProduct(), null, 'deal_of_the_day');
        $bestSellerProducts = $this->safe(fn() => $this->getBestSellerProducts(), collect(), 'best_seller');
        $featuredProducts = $this->safe(fn() => $this->getFeaturedProducts(), collect(), 'featured');
        $specialProducts = $this->safe(fn() => $this->getSpecialProducts(), collect(), 'special');

        // If products paginator failed entirely, show a friendly message
        if ($products === null) {
            session()->flash('error', 'Products could not be loaded right now. Please try again later.');
            $products = Product::where('id', 0)->paginate($this->perPage);
        }

        return view('livewire.user.shop', [
            'categories' => $categories,
            'products' => $products,
            'banners' => $banners,
            'side_banners' => $side_banners,
            'main_side_banner' => $main_side_banner,
            'deal_of_the_day' => $deal_of_the_day,
            'brands' => $brands,
            'bestSellerProducts' => $bestSellerProducts,
            'featuredProducts' => $featuredProducts,
            'specialProducts' => $specialProducts,
        ]);
    }

    /**
     * ✅ Safe executor — kisi bhi closure ko run karta hai
     * Error aaye to log karta hai aur fallback return karta hai
     */
    private function safe(\Closure $callback, $fallback = null, $context = '')
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            $this->logError('safe:' . $context, $e);
            return $fallback;
        }
    }

    /**
     * ✅ Central error logger — har jagah yahi use hoga
     */
    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[Shop::{$method}] " . $e->getMessage(), array_merge([
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'user'  => Auth::id(),
                'url'   => request()->fullUrl(),
            ], $extra));
        } catch (\Throwable $inner) {
            // Agar logging bhi fail ho jaye to chup reh jao
        }
    }
}
