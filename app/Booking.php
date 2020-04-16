<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $table = 'booking';
    public $timestamps = false;
    protected $fillable = ['noPlate','email', 'encryption', 'check_in','booking_at','closed_at'];
    protected $secrets = [
        'title', 'body',
    ];
}
