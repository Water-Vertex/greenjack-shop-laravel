<?php

namespace App\View\Components;

use App\Models\Wishlist;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class ShopHeader extends Component
{
    public $wishlistCount;
    public $isLoggedIn;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->isLoggedIn = Session::has('customer_id');
        $this->wishlistCount = $this->getWishlistCount();
    }

    public function getWishlistCount()
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        $query = Wishlist::whereHas('product', function ($q) {
            $q->where('is_active', true);
        });

        if ($userId) {
            $query->where('customer_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shop-header', [
            'wishlistCount' => $this->wishlistCount,
            'isLoggedIn' => $this->isLoggedIn,
            'settings' => \App\Models\Setting::find(1),
        ]);
    }
}
