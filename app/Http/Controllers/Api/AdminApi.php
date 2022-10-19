<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApi extends Controller
{
    public function GetUser(Request $req)
    {
        $id = $req->id;
        try {
            $data = DB::table('users')->where('id', $id)->first();
            return response()->json([
                'data' => $data,
                'statusApi' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => $e,
                'msg'=>'Tidak Ditemukan',
                'statusApi' => false

            ], 404);
        }
    }
    public function OrderPending()
    {
        try {
            $data = DB::table('tb_cache_order')->join('tb_profile', 'tb_cache_order.id_pelanggan', '=', 'tb_profile.user_id')->where('status', 0)->get();
            $count = DB::table('tb_cache_order')->where('status', 0)->count();
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
            $data = DB::table('tb_cache_order')->join('tb_profile', 'tb_cache_order.id_pelanggan', '=', 'tb_profile.user_id')->where('status', 1)->orderBy('create_pending', 'DESC')->get();
            $count = DB::table('tb_cache_order')->where('status', 1)->count();
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
            $data = DB::table('tb_cache_order')->where('status', 0)->where('id_order', $id)->join('tb_profile', 'tb_cache_order.id_pelanggan', '=', 'tb_profile.user_id')->first();
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
            $data = DB::table('tb_cache_order')->where('status', 1)->where('id_order', $id)->join('tb_profile', 'tb_cache_order.id_pelanggan', '=', 'tb_profile.user_id')->first();
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
        $data = DB::table('tb_order')->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function Checklistpendding(Request $req)
    {
        $ido = $req->ido;
        $idp = $req->idp;
        try {
            DB::table('tb_cache_order')
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
            DB::table('tb_cache_order')
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
            DB::table('tb_cache_order')
                ->where(['id_order' => $ido, 'id_pelanggan' => $idp])
                ->update(['status' => 3, 'create_selesai' => now()]);

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
}
