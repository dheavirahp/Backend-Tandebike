<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['nama','noTelp','email','password'];
    protected $hidden = ['password', 'remember_token', 'api_token'];
}
