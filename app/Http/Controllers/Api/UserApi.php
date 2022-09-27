<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserApi extends Controller
{
    public function GetProfile($id)
    {
        $data = DB::table('tb_profile')->where('id_user ',$id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function AddOrder(Request $req)
    {
        $idpelangan = $req->id_pelangan;
        $keluhan = $req->keluhan;
        $a = $req->a;
        $b = $req->b;

        return response()->json([
            'id_pelangan' => $idpelangan,
            'keluhan' => $keluhan,
            'a' => $a,
            'b' => $b,
            'status' => 0,
        ]);
    }
}
