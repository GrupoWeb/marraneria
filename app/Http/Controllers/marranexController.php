<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;
use App\Model\marranex\canales;
use App\Model\marranex\medida;
use App\Model\marranex\inventario;
use App\Model\marranex\sequences;
use App\Model\marranex\detailMaster;
use App\Model\marranex\sales;



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
                'discount'   =>  $data->contact,
                'status_id' => 1,
            ]);

           DB::commit();

            return response()->json($client,200);
       } catch (\Throwable $th) {
           DB::rollBack();
           return response()->json(false,200);
       }
    }

    public function clientById(Request $request){

        $list = client::select('id','nit','name','surname','address','dpi','phone','company','discount')->where(['status_id' => 1, 'id' => $request->id])->get();

        return response()->json($list,200);
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

    public function listMeasure(){

        $list = medida::select('id','nombre')->where(['estado_id' => 1])->get();

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

    public function listInventory(){
        $inventory = inventario::selectRaw('inventarios.id as code, products.id as codeProduct, products.name as producto, medidas.nombre as medida, inventarios.cantidadMinima as cantidadMinima, inventarios.saldo')
            ->join('products','products.id','=','inventarios.producto_id')
            ->join('medidas','medidas.id','=','inventarios.medida_id')
            ->get();

            return response()->json($inventory, 200);
    }

    public function addKardex(Request $request){
        if(inventario::where(['producto_id' => $request->product, 'medida_id' => $request->measure])->first()){

            $saldo = inventario::where(['producto_id' => $request->product, 'medida_id' => $request->measure])->first();
            $saldoActual = $saldo->saldo + $request->count;

            $data = inventario::where(['producto_id' => $request->product, 'medida_id' => $request->measure])->update(['saldo' => $saldoActual]);

            return response()->json(true,200);

        }else{
            $data = new inventario;
            $data->producto_id = $request->product;
            $data->medida_id = $request->measure;
            $data->saldo = $request->count;

            $data->save();

            return response()->json(true,200);
        }
    }

    public function productById(Request $request){
        $product = product::select('id','name')->where(['id' => $request->id])->get();
        return response()->json($product,200);
    }

    public function getNumberShipping($tabla){
        
        if($data = sequences::where('name','=',$tabla)->select('value')->count() === 0){
            $value = 0;
            $vacio = true;
            
        }else{
            $value = sequences::select('value')->where('name','=', $tabla)->get(); 
            $vacio = false;
            
        };

        if($vacio === true){
            $data = new sequences;
            $data->name = $tabla;
            $data->value = $value + 1;
            $data->save();
        }else{
            $cantidad = sequences::where('name','=',$tabla)->select('value')->count();
            $data = new sequences;
            $data->name = $tabla;
            $data->value = $cantidad+1;
            $data->save();
        }
        return response()->json($data,200);
    }

    public function addSales(Request $request){

        try {
            DB::beginTransaction();
            // add detail table
    
            $sales = new sales;
            $sales->envio = $request->envio;
            $sales->cliente_id = $request->client;
            $sales->tipoOperacion = $request->type;
            $sales->total = $request->total;
            $sales->save();
    
            $sales_id = $sales->id;
            
            $size = sizeof($request->product);
    
            for ($i=0; $i < $size; $i++) { 
                
                $detail = new detailMaster;
                $detail->producto_id = $request->product[$i]['code'];
                $detail->sales_id = $sales_id;
                $detail->cantidad = $request->product[$i]['counts'];
                $detail->precio = $request->product[$i]['price'];
                $detail->descuento = $request->product[$i]['discount'];
                $detail->subtotal = $request->product[$i]['subtotal'];
                $detail->save();

                $saldo = inventario::where(['producto_id' => $request->product[$i]['code']])->first();

                $saldoActual = $saldo->saldo - $request->product[$i]['counts'];
                $inventario = inventario::where(['producto_id' => $request->product[$i]['code']])->update(['saldo' => $saldoActual]);
                
            }


            DB::commit();
    
            return response()->json(true,200);

        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(false, 200);
        }
    }

}
