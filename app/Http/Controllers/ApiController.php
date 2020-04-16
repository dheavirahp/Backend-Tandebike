<?php

namespace App\Http\Controllers;

use rway7\SecureEloquent\HasSecrets;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Users;
use App\Post;
use App\Vehicle;
use App\Vehicle_status;
use App\Booking;
use App\Logbook;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function insertRegister(Request $request){
      $this->validate($request, [
          'nama' => 'required',
          'noTelp' => 'required',
          'email' => 'required|unique:users,email',
          'password' => 'required|min:6'
      ]);

      $data = $request->all();
      $data['api_token'] = Str::random(100);
      $data['password'] = bcrypt($data['password']);

      $user = Users::create($data);

      if($user){
          return $user;
      }
      return $request->all();
    }

    

    public function allBike(){
        return Vehicle::all();
    }

    public function insertBike(Request $request){
        $vehicle = new Vehicle;
        $vehicle->noPlate = $request->noPlate;
        $vehicle->bikeType = $request->bikeType;
        $vehicle->bikeMerk = $request->bikeMerk;

        $data = Vehicle::where('noPlate', $request->noPlate)->first();
        if ($data == null) {
                $vehicle->save();
                return "Insert Successfully";
        }else{
            return "Plate Number Exist";
        }         
    }

    public function deleteBike($noPlate){
        Vehicle::where('noPlate', $noPlate)->delete();
        return "Delete Successfully";
    }

    public function availableBike(){
        $available = Vehicle::leftJoin('Booking', 'Booking.noPlate', '=', 'Vehicle.noPlate')->select('Vehicle.*')->where('Booking.noPlate', '=', NULL)->get();
        return $available;
    }

    public function bookingApp(Request $request){
        $vehicle_status = new Vehicle_status;
        $vehicle_status->noPlate = $request->noPlate;
        $vehicle_status->status = $request->status;

        $booking = new Booking;
        $booking->noPlate = $request->noPlate;
        $booking->email = $request->email;
        $booking->check_in = $request->check_in;
        $booking->Encryption = $request->Encryption;

        $data = Booking::where('noPlate', $request->noPlate)->first();
        // $bike = Vehicle::where('noPlate', $request->noPlate)->first();
        // return $data;
        $bike = Vehicle::where('noPlate', $request->noPlate)->pluck('noPlate')->first();
        //return $bike;
        //return $vehicle_status->noPlate;
        if ($data == null && $bike == $vehicle_status->noPlate){
            $vehicle_status->save();
            $booking->save();
            return "Booking Successfully. Waiting for validation from Bike";
        }else{
            return "Plate Number Exist or Plate Number Not Available";
        }         
    }

    public function engineStarter(Request $request){
        $vehicle_status = new Vehicle_status;
        $vehicle_status->noPlate = $request->noPlate;
        $vehicle_status->status = $request->status;
        $booking = Booking::where('noPlate', '=', $vehicle_status->noPlate)->pluck('check_in')->first();
        if($booking == "1"){
            if(($request->status)=='ON'){
                $vehicle_status->save();
                return 'Engine Start';
            }if(($request->status)=='OFF'){
                $vehicle_status->save();
                return 'Engine Stop';
            }else{
                return 'Error';
            }
        }else{
            return 'Sorry, Validation in progress.';
        }
    }

    public function bookingBike(Request $request){
        $vehicle = new Vehicle;
        $users = new Users;
        $booking = new Booking;

        $idMotor = $request->input('idMotor');
        $idUser = $request->input('idUser');
        $plates = Vehicle::select('noPlate')->where('id',"=", $idMotor)->get();
        $emails = Users::select('email')->where('id',"=", $idUser)->get();
        if($plates){
            if($emails){
                $data = booking::whereIn('noPlate', $plates)
                ->whereIn('email',$emails)
                ->update(array('check_in' => 1));
                return "Ready to Ride";
            }
        }else{
            return "Checking Data, Verification Failed";
        }      
    }

    public function verificationEnc(Request $request){
        $booking = new Booking;
        $noPlate = $request->input('noPlate');
        $valid = Booking::where('noPlate', "=", $noPlate)->pluck('check_in')->first();
        return $valid;
    }

    public function checkEncrypt(Request $request){
        $vehicle = new Vehicle;
        $users = new Users;
        $booking = new Booking;

        $idMotor = $request->input('idMotor');
        $enc = $request->input('Encryption');
        $plates = Vehicle::where('id',"=", $idMotor)->pluck('noPlate')->first();
        $test = Booking::where('noPlate', "=", $plates)->pluck('Encryption')->first();
        if($test){
            if(strlen($enc) == 32){
                $checkEn = booking::where('Encryption' ,"=", $enc)->update(array('check_in' => 1));
                return $enc;
            }else if(strlen($enc) < 32){
                $checkEn = booking::where('noPlate', "=", $plates)->update(array('check_in' => 2));
                return "Encryption invalid";
            }else{
                return "Ngaco";
            }
        }else if($test == ""){
            return "Data not found";
        }else{
            return "Error";
        }     
    }
  
    public function checkoutBike(Request $request){
        $booking = new Booking;
        $current = Carbon::now('+7:00');
        echo $current;
        echo "\n";
        $booking = Booking::where('noPlate', $request->input('noPlate'))->update([
            'closed_at' => $current
        ]);
        // return 'Time Checkout Insert Successfully';

        // $booking = new Booking;
        $booking = Booking::where('noPlate', $request->input('noPlate'))->get();
        foreach($booking as $i => $booking){
            $bookings[$i] = (new Logbook())->forceCreate($booking ->only(['noPlate','email','booking_at','closed_at']));
          //  return 'Insert to Logbook';

        Booking::where('noPlate', $request->input('noPlate'))->delete();
        //return "Checkout Successfully";

        $vehicle_status = new Vehicle_status;
        $vehicle_status->noPlate = $request->noPlate;
        $vehicle_status->status = "1";
        $vehicle_status -> save();
        return 'Bike Available to Rent';
        }
    }
    
    public function AES256(){
           // $en = Crypt::encryptString('4:CI:7');
            //return $en;
            // try {
            //     echo $de = Crypt::decryptString('eyJpdiI6Iko2bFJ5QmlyZDJ5UDZmRFJPMUhJTlFcdTAwM2RcdTAwM2QiLCJtYWMiOiJkNjNkMGQw
            //     ZTdiYjM4YjViMWY1YTcyNjkzZGVkYmQ2MDA2NjIyNWI3Mzg3ZjY0MmM3YWMyZDFhZDY1MGQzNDli
            //     IiwidmFsdWUiOiJEZlY0WURLZXFBaEVpdnNzRy9FOGFnXHUwMDNkXHUwMDNkIn0=');
            // }catch (DecryptException $e) {
            //     return "Tidak ditemukan Data";
            // }
            // $booking = new Booking;
            // $booking = Booking::select('users.id')->where('users.email', '=', $request->input('email') )->get();
            // return $users;
            //$request = Crypt::decryptString($en);
                $post -> secure('encryption-key');
                $post = "1:CI:1";
                $post -> save();
                return $post;
    }

    public function idUser(Request $request){
        $users = new Users;
        $users = Users::select('users.id')->where('users.email', '=', $request->input('email') )->get();
        return $users;
    }

    public function idMotor(Request $request){
        $available = new Vehicle;
        $available = Vehicle::select('vehicle.id')->where('vehicle.noPlate', '=', $request->input('noPlate') )->get();
        return $available;
    }
    //END OF API JSON

    public function users(){
        $users =  Users::select('select * from users');
    //return view('tandebike', compact('users'));
        return view('tandebike', ['users' => $users]);
        //return View::make('tandebike')->with(array('users'=>$users));
    }

    public function getAllBike(){
        $vehicle = Vehicle::leftJoin('Booking', 'Booking.noPlate', '=', 'Vehicle.noPlate')->select('Vehicle.*')->where('Booking.noPlate', '=', NULL)->get();
        return view('admin.tandebike',['vehicle' => $vehicle]);
    }
}