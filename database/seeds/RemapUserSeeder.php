<?php

use Illuminate\Database\Seeder;

use App\User;
use App\AuditStore;
use App\PostedAudit;

class RemapUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = User::all();

        $datas = [['old_user' => 'GELO ISIP', 'new_user' => 'ANGELO ISIP'],
        ['old_user' => 'CLIFF CENDENA', 'new_user' => 'CLIFF CENDANA'],
        ['old_user' => 'MIA CRUZ', 'new_user' => 'MIA ANTENOR CRUZ'],
        ['old_user' => 'STAN RAMOS', 'new_user' => 'STANLEY RAMOS'],
        ['old_user' => 'ANJ CASTILLO', 'new_user' => 'ANGELA CASTILLO'],
        ['old_user' => 'LYCAR FLORES', 'new_user' => 'MIA ANTENOR CRUZ'],
        ];

        foreach ($datas as $key => $row) {
        	$user = User::where('name',$row['old_user'])->first();
        	if(!empty($user)){
        		$new_user = User::where('name', $row['new_user'])->first();
	            AuditStore::where('user_id', $user->id)
	                ->update(['user_id' => $new_user->id]);
	            PostedAudit::where('user_id', $user->id)
	                ->update(['user_id' => $new_user->id]);

	            $user->delete(); 
        	}
               
        }
    }
}
