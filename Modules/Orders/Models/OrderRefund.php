<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    protected $fillable = [
        'order_id',
        'title',
        'name',
        'province_id',

        'province',
        'city_id',
        'city',
        'postal_code',
        'address',

        'phone_number',
        'reason',
        'bank_account',
        'bank_name',
        'pictures'
    ];

    protected $table = 'order_refund';

    public function getBankNameOptions()
    {
        return [
            'BCA'              => 'BCA',
            'BNI'              => 'BNI',
            'Maybank'          => 'Maybank',
            'Mandiri'          => 'Mandiri',
            'Permata'          => 'Permata',
            'Danamon'          => 'Danamon',
            'CIMB Niaga'       => 'CIMB Niaga',
            'BRI'              => 'BRI',
            'Maybank'          => 'Maybank',
            'Hana Bank'        => 'Hana Bank',
        ];
    }

    public function getTitleOptions()
    {
        return [
            'Mr'  => 'Mr.',
            'Mrs' => 'Mrs.',
            'Ms'  => 'Ms.',
        ];
    }

    public function order()
    {
        return $this->belongsTo(\Modules\Orders\Models\Order::class, 'order_id')->withTrashed();
    }

    public function getPictures()
    {
        return explode('|', $this->pictures);
    }

    public function getPicturesUrl()
    {
        $picturesUrl = [];

        if ($pictures = $this->getPictures()) {
            foreach ($pictures as $picture) {
                $picturesUrl[] = asset('image/refund/' . $picture);
            }
        }

        return $picturesUrl;
    }
}
