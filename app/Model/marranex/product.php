<?php

namespace App\Model\marranex;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table = 'products'; 

    protected $fillable = ['name','status_id'];

}
