<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeriesModel extends Pivot
{
    //
    protected $table = 'series_models';
    protected $fillable = ['brand_model_id', 'series_id'];
}
