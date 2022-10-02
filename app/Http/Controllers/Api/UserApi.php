<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserApi extends Controller
{
    public function GetProfile($id)
    {
        try {
            $nama = DB::table('tb_profile')->where('user_id', $id)->value('nama');
            $tgllahir = DB::table('tb_profile')->where('user_id', $id)->value('tgllahir');
            $umur = DB::table('tb_profile')->where('user_id', $id)->value('umur');
            $jkelamin = DB::table('tb_profile')->where('user_id', $id)->value('jkelamin');
            $nohp = DB::table('tb_profile')->where('user_id', $id)->value('nohp');
            $alamat = DB::table('tb_profile')->where('user_id', $id)->value('alamat');

            $datau = DB::table('tb_profile')->where('user_id', $id)->count();
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e,
                'statusApi' => false

            ], 404);
        }

        if ($datau <= 0) {
            return response()->json([
                'data' => null,
                'statusApi' => false
                

            ], 404);
        } else {
            return response()->json([
                'nama' => $nama,
                'tgllahir' => $tgllahir,
                'umur' => $umur,
                'jkelamin' => $jkelamin,
                'nohp' => $nohp,
                'alamat' => $alamat,
                'statusApi' => true

            ], 200);
        }
    }
    public function EditProfile(Request $req)
    {
        $iduser = $req->iduser;
        $nama = $req->nama;
        $tgllahir = $req->tgllahir;
        $umur = $req->umur;
        $jkelamin = $req->jkelamin;
        $nohp = $req->nohp;
        $alamat = $req->alamat;

        $validation = Validator::make($req->all(), [
            //'iduser' => 'required|unique',
            'nama' => 'required',
            //'tgllahir' => 'required',
            'umur' => 'required',
            'jkelamin' => 'required',
            'nohp' => 'required',
            'alamat' => 'required',

        ], [
            'iduser.required' => 'A iduser is required',
            'nama.required' => 'A nama is required',
            'tgllahir.required' => 'A tgllahir is required',
            'umur.required' => 'A umur is required',
            'jkelamin.required' => 'A jkelamin is required',
            'nohp.required' => 'A nohp is required',
            'alamat.required' => 'A alamat is required',


        ]);
        if ($validation->fails()) {

            return response()->json([
                'data' => $validation->getMessageBag(),
                'statusApi' => false
            ], 400);
        } else {
            try {
                $datau = DB::table('tb_profile')->where('user_id', 1)->count();
                if ($datau <= 0) {
                    DB::table('tb_profile')->insert([
                        'user_id' => 1,
                        'nama' => $nama,
                        'tgllahir' => now(),
                        'umur' => $umur,
                        'jkelamin' => $jkelamin,
                        'nohp' => $nohp,
                        'alamat' => $alamat,
                        'created_at' => now()

                    ]);
                    return response()->json([
                        'statusApi' => true
                    ], 200);
                } else {

                    DB::table('tb_profile')
                        ->where('user_id', 1)
                        ->update([
                            'nama' => $nama,
                            'tgllahir' => now(),
                            'umur' => $umur,
                            'jkelamin' => $jkelamin,
                            'nohp' => $nohp,
                            'alamat' => $alamat,
                            'updated_at' => now()
                        ]);
                    return response()->json([
                        'statusApis' => 'upd',

                        'statusApi' => true

                    ], 200);
                }
            } catch (Exception $e) {
                return response()->json([
                    'errors' => $e,
                    'statusApi' => false

                ]);
            }
        }
    }
    public function AddOrder(Request $req)
    {
        $idpelangan = $req->id_pelangan;
        $keluhan = $req->keluhan;
        $jrawat = $req->jrawat;
        $validation = Validator::make($req->all(), [
            //'id_pelangan' => 'required',
            'keluhan' => 'required',
            'jrawat' => 'required',
        ], [
            'id_pelangan.required' => 'A id_pelangan is required',
            'keluhan.required' => 'A keluhan is required',
            'jrawat.required' => 'A jrawat is required',

        ]);
        if ($validation->fails()) {

            return response()->json([
                'data' => $validation->getMessageBag(),
                'statusApi' => false
            ], 400);
        }else{
            try {
                $cek = DB::table('tb_cache_order')->where('id_pelanggan',2)->where('status', 0)->count();
    
                if ($cek >= 1) {
                    return response()->json([
                        'data'=>null,
                        'statusmsg'=>'anda sudah melakukan orderan',
                        'statusApi' => false
                    ],202);
                }else{
                    DB::table('tb_cache_order')->insert([
                        'id_pelanggan' => 2,
                        'jrawat' => $jrawat,
                        'keluhan' => $keluhan,
                        'status' => 0,
                        'create_pending' => now()
        
        
                    ]);
                    return response()->json([
                        'statusApi' => true
                    ],200);
                }
                
    
              
            } catch (Exception $e) {
                return response()->json([
                    'errors' => $e,
                    'statusApi' => false

                ],404);
            }
        }
        
    }
}
