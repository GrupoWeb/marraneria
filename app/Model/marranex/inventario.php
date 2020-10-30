<?php

namespace App\Model\marranex;

use Illuminate\Database\Eloquent\Model;

class inventario extends Model
{
    protected $fillable = ['producto_id','medida_id','saldo'];
}
