<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model
{
    protected $fillable = [
        'url',
        'title',
        'type',
        'changefreq',
        'priority',
        'lastmod',
    ];
}
