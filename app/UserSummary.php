<?php

namespace App;


class UserSummary 
{
    public static function getSummary($audit,$user){

    	$data = new \stdClass();

    	$data->detail = PostedAudit::getUserSummaryDetails($audit->id,$user->id);
    	$data->stores = PostedAudit::getStoresByUser($audit->id,$user->id);

    	$cnt = 0;
    	foreach ($data->stores as $store) {
    		if( $store->perfect_percentage == 100.00){
    			$cnt++;
    		}
    		
    	}
        
        $data->detail->perfect_store_count = $cnt;
        $data->detail->perfect_store_achivement = number_format((($cnt/ $data->stores->count() ) * 100),2);

        $data->doors = PostedAuditCategorySummary::getCategoryDoorsCount($audit,$user);
        
        $data->detail->category_doors = $data->stores->count() * $data->doors['perfect_count'];
        $data->detail->category_door_per = number_format(($data->detail->category_doors / ( $data->stores->count() * $data->doors['total']) * 100 ),2) ;

        return $data;
    }
}
