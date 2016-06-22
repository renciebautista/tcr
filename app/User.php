<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword,EntrustUserTrait{
        EntrustUserTrait::can as may;
        Authorizable::can insteadof EntrustUserTrait;
    }
// namespace App;

// use Illuminate\Foundation\Auth\User as Authenticatable;
// use App\Audit;

// class User extends Authenticatable
// {
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

    public function roles()
    {
        return $this->belongsToMany('App\Role','role_user');
    }

    public function role_name()
    {
        return $this->roles[0]->name;
    }

    public static function auditUsers(Audit $audit){
        $stores = AuditStore::where('audit_id', $audit->id)->get();
        $store_ids = [];
        foreach ($stores as $store) {
            $store_ids[] = $store->user_id;
        }

        $data = self::whereIn('users.id', $store_ids)
            ->orderBy('name')
            ->get();

        foreach ($data as $key => $value) {
            $data[$key]->target;
            $pjp_target = AuditUserPjp::where('user_id', $value->id)
                ->where('audit_id', $audit->id)
                ->first();
            if(!empty($pjp_target)){
                $data[$key]->target = $pjp_target->target;
            }
            
        }
        return $data;
    }

    public function getStatus(){
        if($this->active){
            return 'Active';
        }
        return 'In-active';
    }
}
