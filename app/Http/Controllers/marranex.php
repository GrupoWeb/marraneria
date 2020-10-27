<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;

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
        return view('marranex.show.sale');
    }

    public function showInventory(){
        return view('marranex.show.inventory');
    }

}
