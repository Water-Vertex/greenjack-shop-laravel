<?php

namespace App\Livewire\User;

use App\Models\Product;
use App\Models\Wishlist as WishListModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.shop-layout')]
class Wishlist extends Component
{
    use WithPagination;

    public $addingToCart = null;
    public $removingFromWishlist = null;

    public function mount()
    {
        try {
            if (Auth::check()) {
                $this->migrateGuestWishlist();
            }
        } catch (\Throwable $e) {
            $this->logError('mount', $e);
        }
    }

    protected function migrateGuestWishlist()
    {
        try {
            $sessionId = Session::getId();
            if (!$sessionId) return;

            $guestWishlists = WishListModel::where('session_id', $sessionId)
                ->whereNull('customer_id')
                ->get();

            foreach ($guestWishlists as $guestWishlist) {
                try {
                    $exists = WishListModel::where('customer_id', Auth::id())
                        ->where('product_id', $guestWishlist->product_id)
                        ->exists();

                    if (!$exists) {
                        $guestWishlist->update([
                            'customer_id' => Auth::id(),
                            'session_id' => null,
                        ]);
                    } else {
                        $guestWishlist->delete();
                    }
                } catch (\Throwable $inner) {
                    $this->logError('migrateGuestWishlist:item', $inner, [
                        'wishlist_id' => $guestWishlist->id ?? null,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            $this->logError('migrateGuestWishlist', $e);
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

            $query = WishListModel::where('product_id', $productId);
            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            $wishlistItem = $query->first();

            if ($wishlistItem) {
                $wishlistItem->delete();
                session()->flash('success', 'Removed from wishlist.');
                $this->dispatch('wishlist-updated');
                $this->dispatch('wishlist-removed', $productId);
            } else {
                WishListModel::create([
                    'customer_id' => $userId,
                    'product_id' => $productId,
                    'session_id' => $userId ? null : $sessionId,
                ]);
                session()->flash('success', 'Added to wishlist!');
                $this->dispatch('wishlist-updated');
                $this->dispatch('wishlist-added', $productId);
            }
        } catch (\Throwable $e) {
            $this->logError('toggleWishlist', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not update wishlist. Please try again.');
        }
    }

    public function removeFromWishlist($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $this->removingFromWishlist = $productId;

            $sessionId = Session::getId();
            $userId = Auth::id();

            $query = WishListModel::where('product_id', $productId);
            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            $wishlistItem = $query->first();

            if ($wishlistItem) {
                $wishlistItem->delete();
                session()->flash('success', 'Removed from wishlist.');
            } else {
                session()->flash('error', 'Item not found in wishlist.');
            }

            $this->dispatch('wishlist-updated');
        } catch (\Throwable $e) {
            $this->logError('removeFromWishlist', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not remove item. Please try again.');
        } finally {
            $this->removingFromWishlist = null;
        }
    }

    public function addToCart($productId)
    {
        try {
            if (!is_numeric($productId) || $productId <= 0) {
                session()->flash('error', 'Invalid product.');
                return;
            }

            $this->addingToCart = $productId;

            $product = Product::find($productId);
            if (!$product) {
                session()->flash('error', 'Product not found.');
                $this->addingToCart = null;
                return;
            }

            if ($product->has_variations) {
                $this->addingToCart = null;
                return redirect()->route('shop.details', $product->slug);
            }

            if (($product->stock ?? 0) < 1) {
                session()->flash('error', 'Product not available.');
                $this->addingToCart = null;
                return;
            }

            $cart = Session::get('cart', []);
            if (!is_array($cart)) $cart = [];

            $price = ($product->discounted_price && $product->discounted_price > 0)
                ? $product->discounted_price
                : $product->price;

            if (isset($cart[$productId])) {
                $newQuantity = ($cart[$productId]['quantity'] ?? 0) + 1;
                if ($newQuantity > $product->stock) {
                    session()->flash('error', 'Cannot add more than available stock.');
                    $this->addingToCart = null;
                    return;
                }
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
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
            $this->addingToCart = null;
        } catch (\Throwable $e) {
            $this->logError('addToCart', $e, ['product_id' => $productId]);
            session()->flash('error', 'Could not add to cart. Please try again.');
            $this->addingToCart = null;
        }
    }

    public function getWishlistItemsProperty()
    {
        try {
            $sessionId = Session::getId();
            $userId = Auth::id();

            // ✅ Eager load variations for variation image logic
            $query = WishListModel::with(['product.variations'])
                ->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                });

            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            return $query->paginate(12);
        } catch (\Throwable $e) {
            $this->logError('getWishlistItemsProperty', $e);
            return WishListModel::where('id', 0)->paginate(12);
        }
    }

    public function getWishlistCountProperty()
    {
        try {
            $sessionId = Session::getId();
            $userId = Auth::id();

            $query = WishListModel::whereHas('product', function ($q) {
                $q->where('is_active', true);
            });

            if ($userId) {
                $query->where('customer_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }

            return (int) $query->count();
        } catch (\Throwable $e) {
            $this->logError('getWishlistCountProperty', $e);
            return 0;
        }
    }

    public function render()
    {
        $wishlistItems = null;
        $wishlistCount = 0;

        try {
            $wishlistItems = $this->wishlistItems;
        } catch (\Throwable $e) {
            $this->logError('render:wishlistItems', $e);
            $wishlistItems = WishListModel::where('id', 0)->paginate(12);
        }

        try {
            $wishlistCount = $this->wishlistCount;
        } catch (\Throwable $e) {
            $this->logError('render:wishlistCount', $e);
            $wishlistCount = 0;
        }

        return view('livewire.user.wishlist', [
            'wishlistItems' => $wishlistItems,
            'wishlistCount' => $wishlistCount,
        ]);
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[Wishlist::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => Auth::id(),
                'url' => request()->fullUrl(),
            ], $extra));
        } catch (\Throwable $inner) {}
    }
}
