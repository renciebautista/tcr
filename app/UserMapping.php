<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMapping extends Model
{
    protected $table = 'user_mappings';
    protected $fillable = [
    	'name',
    	'mapped_name'
    ];
}
