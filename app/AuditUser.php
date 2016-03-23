<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = ['audit_id', 'username', 'fullname', 'email', 'password'];
}
