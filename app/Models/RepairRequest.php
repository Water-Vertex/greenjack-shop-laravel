<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RepairRequest extends Model
{
    use HasFactory;

    protected $table = 'repair_requests';

    protected $fillable = [
        'category_id',
        'brand_id',
        'model_id',
        'problems',
        'subtotal',
        'tax',
        'total_price',
        'service_type',
        'preferred_date',
        'preferred_time',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'imei',
        'address',
        'zip_code',
        'city',
        'state',
        'country',
        'message',
        'status',
        'admin_notes',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'paypal_order_id',
        'paypal_capture_id',
        'paid_at',
    ];

    protected $casts = [
        'problems' => 'array', // This will automatically cast JSON to array
        'preferred_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function model()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Accessor for problems list
    public function getProblemsListAttribute()
    {
        $problems = $this->problems;
        if (is_array($problems)) {
            return implode(', ', array_column($problems, 'name'));
        }
        return '';
    }
}
