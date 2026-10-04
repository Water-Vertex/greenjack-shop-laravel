<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $fillable = [
        'page_category',
        'title',
        'slug',
        'description',
        'short_description',
        'content_left',
        'content_right',
        'meta_title',
        'meta_description',
        'schema_markup',
        'featured_image',
        'section_image_left',
        'section_image_right',
        
    ];
}
