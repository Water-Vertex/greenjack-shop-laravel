<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaticSeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_tags',
        'page_schema',
        'focus_keyword',
        'content',
        'image',
        'image_alt',
        'h1_heading',
        'faqs',
        'section_content_left',
        'section_content_right',
        'image_left',
        'image_right',
        'image_left_alt',
        'image_right_alt',
    ];
}
