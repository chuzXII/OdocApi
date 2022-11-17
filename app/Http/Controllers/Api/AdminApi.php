<?php

namespace App\Http\Controllers\Api;

use App\Exports\MultiExport;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class AdminApi extends Controller
{
    public function GetUser(Request $req)
    {
        $id = $req->id;
        try {
            $data = DB::table('users')->select('id','username', )->where('id', $id)->first();
            $opending = DB::table('tb_order')->where('status', 0)->count();
            $oproses = DB::table('tb_order')->where('status', 1)->count();
            return response()->json([
                'data' => $data,
                'opendding' => $opending,
                'oproses' => $oproses,
                'statusApi' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e,
                'msg' => 'Tidak Ditemukan',
                'statusApi' => false

            ], 404);
        }
    }
    public function OrderPending()
    {
        try {
            $data = DB::table('tb_order')->join('tb_profile', 'tb_order.id_pelanggan', '=', 'tb_profile.user_id')->where('status', 0)->get();
            $count = DB::table('tb_order')->where('status', 0)->count();
            if ($count <= 0) {
                return response()->json([
                    'data' => null,
                    'statusApi' => false

                ], 404);
            } else {
                return response()->json([
                    'data' => $data,
                    'statusApi' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e,
                'statusApi' => false
            ], 404);
        }
    }
    public function OrderProses()
    {
        try {
            $data = DB::table('tb_order')->join('tb_profile', 'tb_order.id_pelanggan', '=', 'tb_profile.user_id')->where('status', 1)->orderBy('create_pending', 'DESC')->get();
            $count = DB::table('tb_order')->where('status', 1)->count();
            if ($count <= 0) {
                return response()->json([
                    'data' => null,
                    'statusApi' => false

                ], 404);
            } else {
                return response()->json([
                    'data' => $data,
                    'statusApi' => true
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e,
                'statusApi' => false

            ], 404);
        }
    }
    public function DetailOrderPending($id)
    {
        try {
            $data = DB::table('tb_order')->where('status', 0)->where('id_order', $id)->join('tb_profile', 'tb_order.id_pelanggan', '=', 'tb_profile.user_id')->first();
            return response()->json([
                'data' => [
                    'ido' => $data->id_order,
                    'idp' => $data->id_pelanggan,
                    'nama' => $data->nama,
                    'umur' => $data->umur,
                    'tgllahir' => $data->tgllahir,
                    'jkelamin' => $data->jkelamin,
                    'nohp' => $data->nohp,
                    'alamat' => $data->alamat,
                    'jrawat' => $data->jrawat,
                    'keluhan' => $data->keluhan,


                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e
            ], 404);
        }
    }
    public function DetailOrderProses($id)
    {
        try {
            $data = DB::table('tb_order')->where('status', 1)->where('id_order', $id)->join('tb_profile', 'tb_order.id_pelanggan', '=', 'tb_profile.user_id')->first();
            return response()->json([
                'data' => [
                    'ido' => $data->id_order,
                    'idp' => $data->id_pelanggan,
                    'nama' => $data->nama,
                    'umur' => $data->umur,
                    'tgllahir' => $data->tgllahir,
                    'jkelamin' => $data->jkelamin,
                    'nohp' => $data->nohp,
                    'alamat' => $data->alamat,
                    'jrawat' => $data->jrawat,
                    'keluhan' => $data->keluhan,


                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e
            ], 404);
        }
    }
    public function Rekap()
    {
        return Excel::download(new MultiExport, now() . '.xlsx', ExcelExcel::XLSX);
    }
    public function Checklistpendding(Request $req)
    {
        $ido = $req->ido;
        $idp = $req->idp;
        try {
            $user = DB::table('users')->where('id', $idp)->first();
            $this->Notif($user->tokennof, 'Orderan Telah Di Proses', 'Terimakasih Talah Menunggu,Silahkan Tunggu Dokter Menuju Lokasi Anda');
            DB::table('tb_order')
                ->where(['id_order' => $ido, 'id_pelanggan' => $idp])
                ->update(['status' => 1, 'create_proses' => now()]);
            return response()->json([
                'data' => 'upd',
                'statusApi' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e,
                'statusApi' => false

            ], 404);
        }
    }
    public function Checklistprosess(Request $req)
    {
        $ido = $req->ido;
        $idp = $req->idp;

        try {
            $user = DB::table('users')->where('id', $idp)->first();
            $this->Notif($user->tokennof, 'Orderan Telah Selesai', 'Terimakasih,Jika Masih Memiliki Keluhan Silahkan Order Kembali Kami');
            DB::table('tb_order')
                ->where(['id_order' => $ido, 'id_pelanggan' => $idp])
                ->update(['status' => 2, 'create_selesai' => now()]);

            return response()->json([
                'data' => 'upd',
                's' => $ido,
                'asds' => $idp,
                'msg' => 'Berhasil',
                'statusApi' => true

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'erros' => $e,
                'statusApi' => true
            ], 404);
        }
    }
    public function DeleteChecklistprosess(Request $req)
    {
        $ido = $req->ido;
        $idp = $req->idp;

        try {
            $user = DB::table('users')->where('id', $idp)->first();
            DB::table('tb_order')
                ->where(['id_order' => $ido, 'id_pelanggan' => $idp])
                ->update(['status' => 3, 'create_selesai' => now()]);
            $this->Notif($user->tokennof, 'Orderan Telah Di Batalkan', 'Maaf Orderan Anda Telah Di Batalkan,Silahkan Oder Lagi');

            return response()->json([
                'data' => 'upd',
                's' => $ido,
                'asds' => $idp,
                'msg' => 'Berhasil',
                'statusApi' => true

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'erros' => $e,
                'statusApi' => true

            ], 404);
        }
    }

    public function Countorder()
    {
        $opending = DB::table('tb_order')->where('status', 0)->count();
        $oproses = DB::table('tb_order')->where('status', 1)->count();

        return response()->json([
            'opending' => $opending,
            'oproses' => $oproses
        ]);
    }
    public function Notif($setid, $settitle, $setmsg)
    {
        define('Auth_Key_FCM', 'AAAA7Vdyh-U:APA91bHkjgINYnOZ2VJiJ8aBdpJ_QpxW_yUie3P8Pvyhy46mNCrmE_MUBmJtLX7JxDuuslHR2kZKfJdQNX6jsiCbsULILVnm_mtMfbRPH1q6JtULmp3xX4vF-eGzZH4bZwO0idpgNJ3R');


        $idTokennof = $setid;
        $title = $settitle;
        $msg =  $setmsg;

        $data = json_encode([
            'notification' =>
            [
                'title' => $title,
                'body' => $msg,
                'vibration' => 1000,
                'vibrate' => true,
            ],
            'to' => $idTokennof
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
}
