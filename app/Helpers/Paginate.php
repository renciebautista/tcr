<?php

namespace App\Helpers;

class Paginate {

    public static function show($obj) {
    	if($obj->count() > 1){
    		if($obj->currentPage() == 1){
	        	return '<label class="pull-right">Showing 1 to '.$obj->count().' of '. $obj->total().' entities.</label>';
	        }else{
	        	$first = (($obj->currentPage() - 1) * $obj->perPage()) + 1;
	        	if($obj->currentPage() == $obj->lastPage()){
	        		return '<label class="pull-right">Showing '.$first.' to '.$obj->total().' of '. $obj->total().' entities.</label>';
	        	}else{
	        		return '<label class="pull-right">Showing '.$first.' to '.$obj->perPage() * $obj->currentPage().' of '. $obj->total().' entities.</label>';
	        	}
	        }
    	}else{
    		return '<label class="pull-right">Showing 0 to 0 of '. $obj->total().' entities.</label>';
    	}
        
    }
}