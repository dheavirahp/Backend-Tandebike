<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle_status extends Model
{
    protected $table = 'vehicle_status';
    public $timestamps = false;
    protected $fillable = ['noPlate','status'];
}
