<?php

use App\Http\Controllers\Api\AdminApi;
use App\Http\Controllers\Api\UserApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/profile/{id}',[UserApi::class,'GetProfile']);
Route::post('/editprofile',[UserApi::class,'EditProfile']);

Route::post('/orderadd',[UserApi::class,'AddOrder']);

Route::get('/orderpending',[AdminApi::class,'OrderPending']);
Route::get('/detailorderpending/{id}',[AdminApi::class,'DetailOrderPending']);
Route::get('/orderproses',[AdminApi::class,'OrderProses']);
Route::get('/detailorderproses/{id}',[AdminApi::class,'DetailOrderProses']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
