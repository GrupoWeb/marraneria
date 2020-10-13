<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\marranex\client;
use App\Model\marranex\product;

class marranex extends Controller
{
    public function showclient(){
        return view('marranex.show.client');
    }

    public function showproduct(){
        return view('marranex.show.product');
    }


}
