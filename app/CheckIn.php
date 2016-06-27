<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    public $fillable = ['user_id', 'account', 'customer_code', 'customer', 'area', 'region_code', 'region',
		'distributor_code', 'distributor', 'store_code', 'store_name', 'checkin', 
		'lat', 'long'];
}
