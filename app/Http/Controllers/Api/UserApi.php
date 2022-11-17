<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserApi extends Controller
{
    public function test(){
        return response()->json(['sd'=>'asd']);
    }
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
                'errormsg' => $e,
                'msg' => null,
                'statusApi' => false

            ], 404);
        }

        if ($datau <= 0) {
            return response()->json([
                'data' => null,
                'msg' => 'Profile Tidak Ditemukan,Silahkan Isi Profile Terlebih Dahulu',
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
                $datau = DB::table('tb_profile')->where('user_id', $iduser)->count();
                if ($datau <= 0) {
                    DB::table('tb_profile')->insert([
                        'user_id' => $iduser,
                        'nama' => $nama,
                        'tgllahir' => now(),
                        'umur' => $umur,
                        'jkelamin' => $jkelamin,
                        'nohp' => $nohp,
                        'alamat' => $alamat,
                        'created_at' => now()

                    ]);
                    return response()->json([
                        'statusmsg'=>'Profile Berhasil Di Tambahkan',
                        'statusApi' => true
                    ], 200);
                } else {

                    DB::table('tb_profile')
                        ->where('user_id', $iduser)
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
                        'statusmsg' => 'Profile Berhasil Di Ubah',

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
        $idpelangan = $req->id_pelanggan;
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
        } else {
            try {
                $cek = DB::table('tb_order')->where('id_pelanggan', $idpelangan)->where('status', 0)->count();
                $user = DB::table('users')->where('id', $idpelangan)->first();
                $cekpro = DB::table('tb_profile')->where('user_id', $idpelangan)->count();
                $userrol = DB::table('users')->where('role',1)->pluck('tokennof');
                if($cekpro==0){
                    return response()->json([
                        'data' => null,
                        'statusmsg' => 'Harap Isi Profile Terlebih Dahulu',
                        'statusApi' => false,
                        'statusorder' => 0

                    ], 404);
                }
                else{
                    if ($cek >= 1) {
                        return response()->json([
                            'data' => null,
                            'statusmsg' => 'anda sudah melakukan orderan',
                            'statusApi' => false,
                            'statusorder' => 0
    
                        ], 202);
                    } else {
                        $this->Notif($userrol, 'Orederan Baru', 'Silahkan Cek Ada Orderan Baru');
                        DB::table('tb_order')->insert([
                            'id_pelanggan' => $idpelangan,
                            'jrawat' => $jrawat,
                            'keluhan' => $keluhan,
                            'status' => 0,
                            'create_pending' => now()
                        ]);
                        return response()->json([
                            'data' => $cek,
                            'statusApi' => true
                        ], 200);
                    }
                }
                
            } catch (Exception $e) {
                return response()->json([
                    'data' => $e,
                    'statusApi' => false

                ], 404);
            }
        }
    }
    public function CountOrderUser($id)
    {
        try {
            $cekpending = DB::table('tb_order')->where('id_pelanggan', $id)->where('status', 0)->count();


            if ($cekpending >= 1) {
                return response()->json([
                    'data' => null,
                    'statusmsg' => 'Anda Sudah Melakukan Orderan',
                    'statusApi' => false,
                    'statusorder' => 0
                ], 202);
            }
            $cekproses = DB::table('tb_order')->where('id_pelanggan', $id)->where('status', 1)->count();
            if ($cekproses >= 1) {
                return response()->json([
                    'data' => null,
                    'statusmsg' => 'Orderan Sedang Di Proses',
                    'statusApi' => false,
                    'statusorder' => 1
                ], 202);
            }
            $cekproses = DB::table('tb_order')->where('id_pelanggan', $id)->where('status', 2)->count();
            if ($cekproses >= 1) {
                return response()->json([
                    'data' => null,
                    'statusmsg' => 'Orderan Telah Selasai',
                    'statusApi' => false,
                    'statusorder' => 2
                ], 202);
            }

            return response()->json([
                'data' => 'tidak Itemukan data silahkan order dahulu',
                'statusApi' => false
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e,
                'statusApi' => false

            ], 404);
        }
    }

    public function Notif($setid, $settitle, $setmsg)
    {
        define('Auth_Key_FCM', 'AAAAKh92Eiw:APA91bEIKPUc3j5SncQ0xRmq8W9rde4RfMPDNjcZ-uPZx0t5xNogAjMdWGNe3YH9INcpKYAtiTNd0udEYCzobIuYf_5VbR3yY1wjkrWJCQKjzC1kXAR_L8I7NwnSdroWezvA8kb-wD-f');


        $idTokennof = $setid;
        $title = $settitle;
        $msg = $setmsg;

        $data = json_encode([
            'notification' =>
            [
                'title' => $title,
                'body' => $msg,
                'vibration' => 1000,
                'vibrate' => true,
            ],
            'registration_ids' => $idTokennof
        ]);

        $opts = array(
            'http' =>
            array(
                'method'  => 'POST',
                'header'  => "Content-Type:application/json\r\n" .
                    "Authorization:key=" . Auth_Key_FCM,
                'content' => $data
            )
        );

        $context  = stream_context_create($opts);

        $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
        if ($result) {
            return json_decode($result);
        } else return false;
    }

    // public function testnof(Request $req)
    // {


    //     $idTokennof = $id;
    //     $title = 'TEst Title';
    //     $msg = 'ocatareaxy';

    //     $data = json_encode([
    //         'notification' =>
    //         [
    //             'title' => $title,
    //             'body' => $msg,
    //         ],
    //         'to' => $idTokennof
    //     ]);

    //     $opts = array(
    //         'http' =>
    //         array(
    //             'method'  => 'POST',
    //             'header'  => "Content-Type:application/json\r\n" .
    //                 "Authorization:key=AAAA7Vdyh-U:APA91bHkjgINYnOZ2VJiJ8aBdpJ_QpxW_yUie3P8Pvyhy46mNCrmE_MUBmJtLX7JxDuuslHR2kZKfJdQNX6jsiCbsULILVnm_mtMfbRPH1q6JtULmp3xX4vF-eGzZH4bZwO0idpgNJ3R",
    //             'content' => $data
    //         )
    //     );

    //     $context  = stream_context_create($opts);

    //     $result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
    //     if ($result) {
    //         return json_decode($result);
    //     } else return false;
    // }
}
