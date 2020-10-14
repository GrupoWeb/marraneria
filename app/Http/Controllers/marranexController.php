<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;


class marranexController extends Controller
{
    public function addclient(Request $data){
        try {
            DB::beginTransaction();

            $client = client::create([
                'nit'       =>  $data->nit,
                'name'      =>  $data->name,
                'surname'   =>  $data->surname,
                'address'   =>  $data->address,
                'dpi'       =>  $data->dpi,
                'phone'     =>  $data->phone,
                'company'   =>  $data->company,
                'contact'   =>  $data->contact,
                'status_id' => 1,
            ]);

            DB::commit();

            return response()->json($client,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(false,200);
        }
    }

    public function listClient(){
        
        $list = client::select('id','nit','name','surname','address','dpi','phone','company','contact')->where('status_id',1)->get();

        return response()->json($list,200);
    }

    public function deleteClient(Request $request){
        try {
            DB::beginTransaction();

            $search = client::where('id',$request->id)->update(['status_id' => 2]);

            DB::commit();

            return response()->json($search,200);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function addproduct(Request $data){
        try {
            DB::beginTransaction();

            $product = product::create([
                'name' =>  $data->name,
            ]);

            DB::commit();

            return response()->json($product,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(false,200);
        }
    }
    
    public function listProduct(){
        
        $list = product::all();

        return response()->json($list,200);
    }
}
