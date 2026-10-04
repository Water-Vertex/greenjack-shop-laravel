<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $statusFilter = '';

    public $showViewModal = false;
    public $viewingOrder = null;

    protected $queryString = [
        'search'        => ['except' => ''],
        'perPage'       => ['except' => 10],
        'sortField'     => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter'  => ['except' => ''],
    ];

    public function clearFilters()
    {
        $this->search = '';
        $this->perPage = 10;
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewOrder($id)
    {
        try {
            $order = Order::with(['items', 'shippingDetail'])->findOrFail($id);

            $this->viewingOrder = [
                'id'             => $order->id,
                'order_number'   => $order->order_number,
                'first_name'     => $order->first_name,
                'last_name'      => $order->last_name,
                'email'          => $order->email,
                'phone'          => $order->phone,
                'payment_method' => $order->payment_method,
                'selected_bank'  => $order->selected_bank,
                'mobile_number'  => $order->mobile_number,
                'transaction_id' => $order->transaction_id,
                'subtotal'       => $order->subtotal,
                'shipping_cost'  => $order->shipping_cost,
                'tax_amount'     => $order->tax_amount,
                'grand_total'    => $order->grand_total,
                'status'         => $order->status,
                'order_notes'    => $order->order_notes,
                'created_at'     => $order->created_at->format('F d, Y h:i A'),
                'items'          => $order->items->map(fn($item) => [
                    'product_name' => $item->product_name,
                    'quantity'     => $item->quantity,
                    'price'        => $item->price,
                    'subtotal'     => $item->subtotal,
                ])->toArray(),
                'shippingDetail' => $order->shippingDetail ? [
                    'address'            => $order->shippingDetail->address,
                    'apartment'          => $order->shippingDetail->apartment,
                    'city'               => $order->shippingDetail->city,
                    'state'              => $order->shippingDetail->state,
                    'zip_code'           => $order->shippingDetail->zip_code,
                    'country'            => $order->shippingDetail->country,
                    'different_shipping' => $order->shippingDetail->different_shipping,
                    'shipping_address'   => $order->shippingDetail->shipping_address,
                    'shipping_apartment' => $order->shippingDetail->shipping_apartment,
                    'shipping_city'      => $order->shippingDetail->shipping_city,
                    'shipping_state'     => $order->shippingDetail->shipping_state,
                    'shipping_zip_code'  => $order->shippingDetail->shipping_zip_code,
                    'shipping_country'   => $order->shippingDetail->shipping_country,
                ] : null,
            ];

            $this->showViewModal = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Order not found: ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => $status]);

            if ($this->viewingOrder && $this->viewingOrder['id'] == $id) {
                $this->viewingOrder['status'] = $status;
            }

            session()->flash('success', 'Order status updated!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteOrder($id)
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                session()->flash('error', 'Order not found!');
                return;
            }
            $order->delete();
            session()->flash('success', 'Order deleted successfully!');
            $this->showViewModal = false;
            $this->viewingOrder = null;
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['items'])
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.orders', [
            'orders'      => $orders,
            'totalOrders' => Order::count(),
        ]);
    }
}