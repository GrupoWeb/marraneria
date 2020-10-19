<?php

namespace App\Model\marranex;

use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['nit','name','surname','address','dpi','phone','company','discount','status_id'];
}
