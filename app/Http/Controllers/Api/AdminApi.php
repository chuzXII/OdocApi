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
            $data = DB::table('tb_cache_order')->where('status',0)->get();
            $count = DB::table('tb_cache_order')->where('status',0)->count();
            if($count<=0){
                return response()->json([
                    'data' => null,
                    'statusApi' => false

                ],404);
            }
            else{
                return response()->json([
                    'data' => $data,
                    'statusApi' => true
                ],200);
            }
            
        }
        catch(Exception $e){
            return response()->json([
                'errors' => $e,
                'statusApi' => false
            ],404);
        }
        

       
    }
    public function OrderProses(){
        try{
            $data = DB::table('tb_cache_order')->where('status',1)->get();
            $count = DB::table('tb_cache_order')->where('status',1)->count();
            if($count<=0){
                return response()->json([
                    'data' => null,
                    'statusApi' => false

                ],404);
            }
            else{
                return response()->json([
                    'data' => $data,
                    'statusApi' => true
                ],200);
            }
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e,
                'statusApi' => false

            ],404);
        }
        
        
    }
    public function DetailOrderPending($id){
        try{
            $data = DB::table('tb_cache_order')->where('id_pelanggan',$id)->where('status',0)->get();

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
            $data = DB::table('tb_cache_order')->where('id_pelanggan',$id)->where('status',1)->get();

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
        $data = DB::table('tb_order')->get();
        
        return response()->json([
            'data' => $data
        ]);
    }
    public function Checklistpending($id){
        DB::table('tb_cache_order')
        ->where('id_pelanggan', 1)
        ->update(['status'=>1]);

    return response()->json([
        'statusApis' => 'upd',
        'statusApi' => true

    ], 200);
    }
    public function Checklistprosess($id){
        DB::table('tb_cache_order')
        ->where('id_pelanggan', 1)
        ->update(['status'=>2]);

    return response()->json([
        'statusApis' => 'upd',
        'statusApi' => true

    ], 200);
    }
}
