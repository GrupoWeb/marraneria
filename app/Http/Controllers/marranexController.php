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
            $idSale = $sales->id;
    
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
    
            return response()->json($request->envio,200);

        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(false, 200);
        }
    }

    public function GetBarCodeById(Request $request){

        $sales = sales::select('sales.id','sales.envio as code','clients.name as cliente','sales.tipoOperacion as tipo','sales.created_at as fecha','sales.total')->where(['envio' => $request->id])
            ->join('clients','clients.id','=','sales.cliente_id')
            ->get();

        $detalle = detailMaster::select('products.name as producto','detail_masters.cantidad','detail_masters.precio','detail_masters.descuento','detail_masters.subtotal')->where(['sales_id' => $sales[0]->id])
            ->join('products','products.id','=','detail_masters.producto_id')
            ->get();

        // dd($detalle);


            $html = '
            <!DOCTYPE html>
                <html lang="es">
                <head>
                    <link rel="stylesheet" href="./bs3.min.css">
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
                    <title>Envios Marranex</title>
                </head>
                <body>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-10 ">
                            <h1>Envio</h1>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-2 text-center">
                            <strong>Fecha: '.  $sales[0]->fecha.'</strong>
                            <br>
                            <br>
                            <strong>Envio No. '.  $sales[0]->code.'</strong>
                            <br>
                            
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center" style="margin-bottom: 2rem;">
                        <div class="col-xs-6">
                            <h1 class="h2">Cliente: '.  $sales[0]->cliente.'</h1>
                        </div>
                    </div>
                    <div class="row text-center" style="margin-bottom: 2rem;">
                        <div class="col-xs-6">
                            <h1 class="h2">Operación: '.  $sales[0]->tipo.'</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-condensed table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>SubTotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                ';
                                
                                foreach ($detalle as $key) {
                                    $html .= '<tr><td>'.$key->producto.'</td>';
                                    $html .= '<td>'.$key->cantidad.'</td>';
                                    $html .= '<td> Q. '.$key->precio.'</td>';
                                    $html .= '<td> Q.'.$key->subtotal.'</td></tr>';
                                }

                                $html .='
                                
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <h4>Total</h4></td>
                                    <td>
                                        <h4>Q. '.$sales[0]->total.'</h4>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <p class="h5"></p>
                        </div>
                    </div>
                </div>
                </body>
                </html>
            ';


        

        $pdf = \PDF::loadHtml($html);
        $html2 = 'test';
        // $paper_size = array(0,0,144,308);
        // $pdf->setPaper($paper_size, 'landscape');
        // $pdf->setOptions(['dpi' => 120, 'defaultFont' => 'sans-serif']);
        // return $pdf->stream("Códigos de Barra".'.pdf'); 
        $path = public_path('pdf/');
        $fileName =  'Envio No '.$sales[0]->code . '.' . 'pdf' ;
        $pdf->save($path . '/' . $fileName);
        // return $pdf->stream($fileName);
        // return $pdf->output();
        return $fileName;
        
    }


}
