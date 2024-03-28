<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Products\Models\Review;

class OrderDetail extends Model
{
    protected $fillable = [
                            'order_id',
                            'product_id',
                            'product_name',
                            'packaging_size_id',
                            'packaging_size_name',
                            //'category_id',
                            //'category_name',
                            'quantity',
                            'price',
                            'weight'
                        ];

    public function getPriceFormat()
    {
        return number_format($this->price, 2, ',', '.');
    }

    public function order()
    {
        return $this->belongsTo(\Modules\Orders\Models\Order::class, 'order_id')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Products\Models\Product::class, 'product_id')->withTrashed();
    }

    public function packagingSize()
    {
        return $this->belongsTo(\Modules\PackagingSizes\Models\PackagingSize::class, 'packaging_size_id')->withTrashed();
    }

    public function review()
    {
        return $this->hasOne('Modules\Products\Models\Review','order_details_id');
    }

    public function getReview()
    {
        return ($this->review ? $this->review : new Review);
    }

    // public function getReview($order_details_id,$product_id)
    // {
    //     return \Modules\Products\Models\Review::where('order_details_id', $order_details_id)->where('product_id', $product_id)->first();
    // }

    public function scopeBestSellingProducts($query, $params)
    {
        if (isset($params['from']) && isset($params['to'])) {
            $query->whereBetween('orders.date', [$params['from'], $params['to']]);
        }

        return $query;
    }

}
