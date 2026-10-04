<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    protected $fillable = [
        'order_id',
        'address', 'apartment', 'city', 'state', 'zip_code', 'country',
        'different_shipping',
        'shipping_address', 'shipping_apartment',
        'shipping_city', 'shipping_state', 'shipping_zip_code', 'shipping_country',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}