<?php

namespace App;

use rway7\SecureEloquent\HasSecrets;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSecrets;

    /**
     * The attributes that need to be encrypted.
     *
     * @var array
     */
    protected $secrets = [
        'title', 'body',
    ];
}