<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApi extends Controller
{
    public function OrderPending(){
        try{
            $data = DB::table('orderdoc')->where('status',0)->get();
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
        

        return response()->json([
            'data' => $data
        ]);
    }
    public function OrderProses(){
        try{
            $data = DB::table('orderdoc')->where('status',1)->get();

        }
        catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
        
        return response()->json([
            'data' => $data
        ]);
    }
    public function DetailOrderPending($id){
        try{
            $data = DB::table('orderdoc')->where('id_order',$id)->where('status',0)->get();

        }
        catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
        if($data == null){
            return response()->json([
                'data' => 'kosonh'
            ]); 
        }
        
        return response()->json([
            'data' => $data
        ]);
    }
    public function DetaiOrderProses($id){
        try{
            $data = DB::table('orderdoc')->where('id_order',$id)->where('status',1)->get();

        }
        catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
       
        
        return response()->json([
            'data' => $data
        ]);
    }

    public function Rekap(){
        $data = DB::table('orderdoc')->where('status',1)->get();
        
        return response()->json([
            'data' => $data
        ]);
    }
}
