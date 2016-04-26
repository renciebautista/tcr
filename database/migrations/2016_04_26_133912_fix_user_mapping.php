<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\AuditStore;
use App\PostedAudit;

class FixUserMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::all();
        foreach ($users as $key => $user) {
            if($user->name == 'GELO ISIP'){
                $new_user = User::where('name', 'ANGELO ISIP')->first();
                AuditStore::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                PostedAudit::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);

                $user->delete();    
            }

            if($user->name == 'CLIFF CENDENA'){
                $new_user = User::where('name', 'CLIFF CENDANA')->first();
                AuditStore::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                PostedAudit::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);

                $user->delete();    
            }

            if($user->name == 'MIA CRUZ'){
                $new_user = User::where('name', 'MIA ANTENOR CRUZ')->first();
                AuditStore::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                PostedAudit::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                $user->delete();  
            }

            if($user->name == 'STAN RAMOS'){
                $new_user = User::where('name', 'STANLEY RAMOS')->first();
                AuditStore::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                PostedAudit::where('user_id', $user->id)
                    ->update(['user_id' => $new_user->id]);
                $user->delete();  
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
