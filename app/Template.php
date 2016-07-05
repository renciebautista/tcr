<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public function getStatus(){
        if($this->active){
            return 'Active';
        }
        return 'In-active';
    }
}
