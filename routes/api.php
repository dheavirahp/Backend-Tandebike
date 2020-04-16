<?php

use Illuminate\Http\Request;
use Carbon\Carbon;
//use Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request){
    $valid = Auth::attempt($request->all());

    if($valid){
        $user = Auth::user();
        $user->api_token = Str::random(100);
        $user->save();
        $user->makeVisible('api_token');
        $user->success = 1;

        return $user;
    }

    return response()->json([
        'message' => 'Email & Password doesn\'t match'
    ], 404);
});

Route::get('allBike', 'ApiController@allBike');
Route::get('availableBike', 'ApiController@availableBike');
Route::get('checkoutSystem', 'ApiController@checkoutSystem');
Route::get('enc', 'ApiController@AES256');
Route::post('/user/idUser', 'ApiController@idUser');
Route::post('/user/idMotor', 'ApiController@idMotor');
Route::post('/user/register', 'ApiController@insertRegister');
Route::post('/user/insertBike', 'ApiController@insertBike');
Route::post('/user/bookingApp', 'ApiController@bookingApp');
Route::post('/user/verificationEnc', 'ApiController@verificationEnc');
Route::post('/user/engineStarter', 'ApiController@engineStarter');
Route::post('/user/bookingBike', 'ApiController@bookingBike');
Route::post('/user/checkEncrypt', 'ApiController@checkEncrypt');
Route::post('/user/checkoutBike', 'ApiController@checkoutBike');
Route::delete('/user/deleteBike/{noPlate}', 'ApiController@deleteBike');

//UNTUK WEBVIEW
// Route::post('/login/{email}', 'ApiController@checkLogin');