<?php

use App\Http\Controllers\Api\AdminApi;
use App\Http\Controllers\Api\AuthApi;
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
Route::get('/countorderuser/{id}',[UserApi::class,'CountOrderUser']);

Route::post('/editprofile',[UserApi::class,'EditProfile']);

Route::post('/orderadd',[UserApi::class,'AddOrder']);

Route::post('/getuseradmin',[AdminApi::class,'GetUser']);
Route::get('/orderpending',[AdminApi::class,'OrderPending']);
Route::get('/detailorderpending/{id}',[AdminApi::class,'DetailOrderPending']);
Route::get('/orderproses',[AdminApi::class,'OrderProses']);
Route::get('/detailorderproses/{id}',[AdminApi::class,'DetailOrderProses']);
Route::post('/checklistpendding',[AdminApi::class,'Checklistpendding']);
Route::post('/checklistprosess',[AdminApi::class,'Checklistprosess']);
Route::post('/deletechecklistprosess',[AdminApi::class,'DeleteChecklistprosess']);




Route::post('/register',[AuthApi::class,'Register']);
Route::post('/login',[AuthApi::class,'Auth']);



Route::group(['middleware' => 'auth:sanctum'],function(){
Route::get('/logout',[AuthApi::class,'Logout']);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
