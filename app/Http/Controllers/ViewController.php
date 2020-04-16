<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Vehicle;
use App\Vehicle_status;
use App\Booking;
use App\Logbook;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ViewController extends Controller
{
    public function index(){
        $users = Users::all();
        return view('tandebike')->with('users', $users );    }
}
