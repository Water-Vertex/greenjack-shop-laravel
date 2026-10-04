<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id',
        'first_name', 'last_name', 'email', 'phone',
        'payment_method', 'selected_bank', 'mobile_number', 'transaction_id',
        'subtotal', 'shipping_cost', 'tax_amount', 'grand_total','paypal_order_id','paypal_capture_id','stripe_session_id','stripe_payment_intent_id','paid_at',
        'status', 'order_notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingDetail()
    {
        return $this->hasOne(ShippingDetail::class);
    }
}
