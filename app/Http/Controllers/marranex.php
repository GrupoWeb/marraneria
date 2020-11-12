<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;
use App\Model\marranex\sequences;

class marranex extends Controller
{
    public function showClient(){
        return view('marranex.show.client');
    }

    public function showProduct(){
        return view('marranex.show.product');
    }

    public function showChannel(){
        return view('marranex.show.channel');
    }

    public function showSale(){

        $secuencia = $this->getNumberShipping("sales");
        $secuencia = json_decode(json_encode($secuencia));
        $envio = $secuencia->original->value;
        return view('marranex.show.sale',["id" => $envio]);
    }

    public function showInventory(){
        return view('marranex.show.inventory');
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
            $cantidad = $data = sequences::where('name','=',$tabla)->select('value')->count();
            $data = new sequences;
            $data->name = $tabla;
            $data->value = $cantidad+1;
            $data->save();
        }
        return response()->json($data,200);
    }

    public function reporteEnvio(){
        return view('marranex.show.renvios');
    }

    public function showDebts(){
        return view('marranex.show.debt');
    }

}
