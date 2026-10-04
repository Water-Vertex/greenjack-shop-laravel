<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',        // ✅ Add this - Link to specific variation
        'product_name',
        'product_sku',          // ✅ Add this - Store SKU at time of order
        'price',
        'discounted_price',     // ✅ Add this - Store discounted price if any
        'quantity',
        'subtotal',
        'attributes',           // ✅ Add this - Store selected attributes as JSON
        'attribute_string',     // ✅ Add this - Store human-readable attributes string
        'image',                // ✅ Add this - Store product/variation image
    ];

    protected $casts = [
        'attributes' => 'array',  // ✅ Cast attributes to array
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    // ✅ Helper to get formatted attributes
    public function getFormattedAttributesAttribute()
    {
        if (empty($this->attributes)) {
            return '-';
        }

        if (is_string($this->attributes)) {
            $attrs = json_decode($this->attributes, true);
        } else {
            $attrs = $this->attributes;
        }

        if (is_array($attrs)) {
            return implode(', ', $attrs);
        }

        return '-';
    }
}
