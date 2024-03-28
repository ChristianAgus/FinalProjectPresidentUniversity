<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'size',
        'uom',
        'description',
        'specification',
        'price',
        'image',
        'status',
        'cat_name'
    ];

    public function categories()
    {   
        return $this->belongsTo('App\Models\MsCategory', 'category_id');
    }
    
}
