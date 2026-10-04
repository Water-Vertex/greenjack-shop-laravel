<?php

namespace App\Livewire\User;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

#[Layout('components.shop-layout')]
class OrderDetail extends Component
{
    public $order;
    public $orderId;

    public function mount($orderId)
    {
        $this->orderId = $orderId;

        // Check if customer is logged in
        if (!Session::has('customer_id')) {
            return redirect()->route('customer.login')->with('error', 'Please login to access your orders.');
        }

        // Get the order with all relationships
        $this->order = Order::with(['items', 'shippingDetail', 'customer'])
            ->where('id', $orderId)
            ->where('customer_id', Session::get('customer_id'))
            ->first();

        if (!$this->order) {
            return redirect()->route('customer.account')->with('error', 'Order not found.');
        }
    }

    public function render()
    {
        return view('livewire.user.order-detail');
    }
}
