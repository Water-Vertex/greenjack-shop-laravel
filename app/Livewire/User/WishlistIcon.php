<?php

namespace App\Livewire\User;

use App\Models\Wishlist;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('components.shop-layout')]
class WishlistIcon extends Component
{
    public $wishlistCount = 0;
    public $wishlistItems = [];

    protected $listeners = ['wishlist-updated' => 'loadWishlist'];

    public function mount()
    {
        try {
            $this->loadWishlist();
        } catch (\Throwable $e) {
            $this->logError('mount', $e);
            $this->wishlistCount = 0;
            $this->wishlistItems = [];
        }
    }

    public function loadWishlist()
{
    try {
        $sessionId = Session::getId();
        $userId = Auth::id();

        $query = Wishlist::with(['product.variations'])  // ✅ Eager load variations
            ->whereHas('product', function ($q) {
                $q->where('is_active', true);
            });

        if ($userId) {
            $query->where('customer_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $wishlistCollection = $query->get();

        $this->wishlistItems = $wishlistCollection ? $wishlistCollection->toArray() : [];
        $this->wishlistCount = is_array($this->wishlistItems) ? count($this->wishlistItems) : 0;

    } catch (\Throwable $e) {
        $this->logError('loadWishlist', $e);
        $this->wishlistItems = [];
        $this->wishlistCount = 0;
    }
}

    public function removeFromWishlist($productId)
    {
        try {
            if (!$productId || !is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
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
                $this->loadWishlist();
                $this->dispatch('wishlist-updated');
                $this->dispatch('wishlist-removed', productId: $productId);
            } else {
                session()->flash('error', 'Item not found in wishlist.');
            }

        } catch (\Throwable $e) {
            $this->logError('removeFromWishlist', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not remove item. Please try again.');
        }
    }

    public function addToCart($productId)
    {
        try {
            if (!$productId || !is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $product = Product::find($productId);

            if (!$product) {
                session()->flash('error', 'Product not found.');
                return;
            }

            // ✅ Variable product → redirect to details
            if ($product->has_variations) {
                return redirect()->route('shop.details', $product->slug);
            }

            if (($product->stock ?? 0) < 1) {
                session()->flash('error', 'Product not available.');
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
                $newQuantity = ($cart[$productId]['quantity'] ?? 0) + 1;
                if ($newQuantity > $product->stock) {
                    session()->flash('error', 'Cannot add more than available stock.');
                    return;
                }
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
                // ✅ Consistent cart schema
                $cart[$productId] = [
                    'id' => $productId,
                    'product_id' => $productId,
                    'variation_id' => null,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'price' => $price,
                    'quantity' => 1,
                    'stock' => $product->stock,
                    'sku' => $product->sku,
                    'variation_attributes' => '',
                ];
            }

            Session::put('cart', $cart);
            session()->flash('success', 'Product added to cart!');
            $this->dispatch('cart-updated');

        } catch (\Throwable $e) {
            $this->logError('addToCart', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not add to cart. Please try again.');
        }
    }

    public function render()
    {
        try {
            return view('livewire.user.wishlist-icon', [
                'wishlistItems' => $this->wishlistItems ?? [],
                'wishlistCount' => $this->wishlistCount ?? 0,
            ]);
        } catch (\Throwable $e) {
            $this->logError('render', $e);
            return view('livewire.user.wishlist-icon', [
                'wishlistItems' => [],
                'wishlistCount' => 0,
            ]);
        }
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[WishlistIcon::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => Auth::id(),
                'url' => request()->fullUrl(),
            ], $extra));
        } catch (\Throwable $inner) {
            // silent
        }
    }
}
