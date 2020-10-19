<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;
use App\Model\marranex\canales;


class marranexController extends Controller
{
    public function addclient(Request $data){
//        try {
//            DB::beginTransaction();

            $client = client::create([
                'nit'       =>  $data->nit,
                'name'      =>  $data->name,
                'surname'   =>  $data->surname,
                'address'   =>  $data->address,
                'dpi'       =>  $data->dpi,
                'phone'     =>  $data->phone,
                'company'   =>  $data->company,
                'discount'   =>  $data->contact,
                'status_id' => 1,
            ]);

//            DB::commit();

            return response()->json($client,200);
//        } catch (\Throwable $th) {
//            DB::rollBack();
//            return response()->json(false,200);
//        }
    }

    public function listClient(){

        $list = client::select('id','nit','name','surname','address','dpi','phone','company','discount')->where('status_id',1)->get();

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
                'status_id' => 1,
            ]);

            DB::commit();

            return response()->json($product,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(false,200);
        }
    }

    public function listProduct(){

        $list = product::select('id','name')->where(['status_id' => 1])->get();

        return response()->json($list,200);
    }

    public function editProduct(Request $request){
        try {
            DB::beginTransaction();

            $update = product::where('id',$request->id)->update(['name' => $request->name]);

            DB::commit();

            return response()->json($update,200);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function deleteProduct(Request $request){

        try {
                DB::beginTransaction();

            $delete = product::where('id',$request->id)->update(['status_id' => 2]);

            DB::commit();

            return response()->json($delete,200);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function addChannel(Request $request){
        try {
            DB::beginTransaction();

            $channel = canales::create([
                'name'      =>  $request->name,
                'weights'   =>  $request->weights,
                'cost'      =>  $request->cost,
                'decrease'  =>  $request->decrease
            ]);

            DB::commit();

            return response()->json($channel,200);
        }catch(\Throwable $th){
            DB::rollBack();
            return response()->json(false,200);
        }
    }

    public function listChannel(){
        $data = canales::selectRaw('name, weights, CONCAT("Q " , cost ) AS cost, decrease')->get();

        return response()->json($data,200);
    }
}
