<?php

namespace App\Livewire\User;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.shop-layout')]
class ShopPaymentCancelled extends Component
{
    public $order;
    public $message;

    public function mount($id = null)
    {
        if (!$id) {
            return redirect()->route('shop');
        }

        $this->order = Order::with(['items', 'shippingDetail'])
            ->find($id);

        if (!$this->order) {
            return redirect()->route('shop')->with('error', 'Order not found.');
        }

        $this->message = 'Your payment was cancelled. No charges were made to your account.';
    }

    public function retryPayment()
    {
        // Redirect back to checkout with the same cart
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.user.shop-payment-cancelled', [
            'order' => $this->order
        ]);
    }
}
