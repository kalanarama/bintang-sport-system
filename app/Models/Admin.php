<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $fillable = ['username_admin', 'password_admin'];
    protected $hidden = ['password_admin'];

    public function getAuthPassword()
    {
        return $this->password_admin;
    }
}