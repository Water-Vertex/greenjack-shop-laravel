<?php
// app/Models/ProductAttributeValue.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAttributeValue extends Pivot
{
    protected $table = 'product_attribute_values';
    protected $fillable = ['product_id', 'attribute_value_id'];
}
