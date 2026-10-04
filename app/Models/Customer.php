<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'postalcode',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

     public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Helper method to get full name
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    // Scope for active customers
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
