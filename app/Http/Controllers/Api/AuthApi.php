<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApi extends Controller
{
    public function Register(Request $req)
    {
        $username = $req->username;
        $password = $req->password;
       


        $validation = Validator::make($req->all(), [
            'username' => 'required|unique:users',
            'password' => 'required|same:konpassword',
            'konpassword' => 'required',

        ], [
            'username.required' => 'A username is required',
            'password.required' => 'A password is required',
            'username.unique' => 'A username is unique',
            'konpassword.required' => 'A konpassword is required',


        ]);
        if ($validation->fails()) {
            return response()->json([
                'data' => $validation->errors(),
                'statusApi' => false
            ], 400);
        } else {
            try {
                $passenc = Hash::make($password);
                DB::table('users')->insert([
                    'username' => $username,
                    'password' => $passenc,
                    'created_at' => now()
                ]);
                return response()->json([
                    'data' => 'berhasil',
                    'msg'=>'Registrasi Berhasil',
                    'statusApi' => true
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'data' => $e,
                    'statusApi' => false
                ], 404);
            }
        }
    }

    public function Auth(Request $req)
    {
        $username = $req->username;
        $password = $req->password;
        $tokennof = $req->tokennof;


        $validation = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'A username is required',
            'password.required' => 'A password is required',


        ]);
        if ($validation->fails()) {
            return response()->json([
                'data' => $validation->errors(),
                'statusApi' => false
            ], 400);
        } else {
            try {
                $user = User::where('username', $username)->first();
                if (!Hash::check($password, $user->password)) {
                    return response()->json([
                        'data' => 'Unathorized',
                        'msg'=> 'Password Salah',
                        'statusApi' => false
                    ], 401);
                }
                DB::table('users')
                ->where(['username' => $username])
                ->update(['tokennof' => $tokennof, 'updated_at' => now()]);
                $tokenResult = $user->createToken('token-auth')->plainTextToken;

                return response()->json([
                    'data' => [
                        'id' => $user->id,
                        'role' => $user->role,
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                    ],
                    'status' => 'success',
                    'msg' => 'Login successfully',
                    'errors' => null,

                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'data' => $e,
                    'msg'=> 'User Tidak Ditemukan',
                    'statusApi' => false
                ], 404);
            }
        }
    }

    public function Logout(Request $req)
    {
        try {
            $user = $req->user();
            $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Logout successfully',
                'statusApi' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'msg' => $e,
                'errors' => null,
                'content' => null,
                'statusApi' => false
            ], 400);
        }
    }

    // public function checkLogin(Request $req){
    //     $count =DB::table('personal_access_tokens')->where('tokenable_id',$req->id)->count();
    //     return response()->json([
    //         'count'=>$count,
    //     ]);
    // }
    
}
