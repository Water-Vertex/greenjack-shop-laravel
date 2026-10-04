<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartIcon extends Component
{
    public $cartCount = 0;
    public $cartTotal = 0;
    public $cartItems = [];

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount()
    {
        try {
            $this->loadCart();
        } catch (\Throwable $e) {
            $this->logError('mount', $e);
            $this->cartCount = 0;
            $this->cartTotal = 0;
            $this->cartItems = collect();
        }
    }

    public function loadCart()
    {
        try {
            $cart = Session::get('cart', []);

            if (!is_array($cart)) {
                $cart = [];
                Session::put('cart', []);
            }

            $cleanCart = [];
            foreach ($cart as $key => $item) {
                if (!is_array($item)) {
                    continue;
                }

                if (!isset($item['price'], $item['quantity'])) {
                    continue;
                }

                $qty = (int) $item['quantity'];
                if ($qty <= 0) {
                    continue;
                }

                $cleanCart[$key] = $item;
                $cleanCart[$key]['quantity'] = $qty;
                $cleanCart[$key]['price'] = (float) $item['price'];
            }

            if (count($cleanCart) !== count($cart)) {
                Session::put('cart', $cleanCart);
            }

            // ✅ Collection — blade me ->count() chalega
            $this->cartItems = collect($cleanCart);
            $this->cartCount = (int) $this->cartItems->sum('quantity');
            $this->cartTotal = (float) $this->cartItems->sum(function ($item) {
                return ((float) $item['price']) * ((int) $item['quantity']);
            });

        } catch (\Throwable $e) {
            $this->logError('loadCart', $e);
            $this->cartItems = collect();
            $this->cartCount = 0;
            $this->cartTotal = 0;
        }
    }

    public function removeItem($productId)
    {
        try {
            if (empty($productId)) {
                return;
            }

            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                Session::put('cart', $cart);
                $this->loadCart();
                $this->dispatch('cart-updated');
                $this->dispatch('toast', message: 'Item removed from cart.', type: 'success');
            } else {
                $this->dispatch('toast', message: 'Item not found in cart.', type: 'error');
            }

        } catch (\Throwable $e) {
            $this->logError('removeItem', $e, ['product_id' => $productId]);
            $this->dispatch('toast', message: 'Could not remove item. Please try again.', type: 'error');
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        try {
            if (empty($productId)) {
                return;
            }

            $quantity = (int) $quantity;

            $cart = Session::get('cart', []);
            if (!is_array($cart)) {
                $cart = [];
            }

            if (!isset($cart[$productId])) {
                $this->dispatch('toast', message: 'Item not found in cart.', type: 'error');
                return;
            }

            if ($quantity <= 0) {
                $this->removeItem($productId);
                return;
            }

            $maxStock = isset($cart[$productId]['stock'])
                ? (int) $cart[$productId]['stock']
                : $quantity;

            if ($maxStock > 0 && $quantity > $maxStock) {
                $quantity = $maxStock;
                $this->dispatch('toast', message: "Only {$maxStock} items available in stock.", type: 'warning');
            }

            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cart-updated');

        } catch (\Throwable $e) {
            $this->logError('updateQuantity', $e, [
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            $this->dispatch('toast', message: 'Could not update quantity. Please try again.', type: 'error');
        }
    }

    public function render()
    {
        try {
            return view('livewire.user.cart-icon');
        } catch (\Throwable $e) {
            $this->logError('render', $e);
            return view('livewire.user.cart-icon')->with('renderError', true);
        }
    }

    private function logError(string $method, \Throwable $e, array $extra = []): void
    {
        try {
            Log::error("[CartIcon::{$method}] " . $e->getMessage(), array_merge([
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user' => auth()->id(),
                'url' => request()->fullUrl(),
            ], $extra));
        } catch (\Throwable $inner) {
            // silent
        }
    }
}
