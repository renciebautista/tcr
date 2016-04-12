<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Audit;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function auditUsers(Audit $audit){
        $stores = AuditStore::where('audit_id', $audit->id)->get();
        $store_ids = [];
        foreach ($stores as $store) {
            $store_ids[] = $store->user_id;
        }

        return self::whereIn('users.id', $store_ids)
            ->orderBy('name')
            ->get();
    }
}
