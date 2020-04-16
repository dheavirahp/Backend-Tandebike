<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbook';
    public $timestamps = false;
    protected $fillable = ['noPlate','email', 'booking_at', 'closed_at'];
}
