<?php
// app/Models/Variation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $table = 'variations';

    protected $fillable = [
        'product_id',
        'sku',
        'upc',
        'asin',
        'price',
        'discounted_price',
        'stock',
        'moq',
        'image',
        'is_active',
        'attributes'  // ✅ JSON column storing attribute_value_ids
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(VariationImage::class);
    }

    // ✅ Get attribute values from JSON column
    public function getAttributeValuesAttribute()
    {
        if (empty($this->attributes) || !is_array($this->attributes)) {
            return collect([]);
        }

        return AttributeValue::with('attribute')
            ->whereIn('id', $this->attributes)
            ->get();
    }

    // ✅ Get formatted string
    public function getAttributeStringAttribute()
    {
        $values = $this->attribute_values;
        return $values->map(function($value) {
            return $value->attribute->name . ': ' . $value->value;
        })->implode(', ');
    }
}
