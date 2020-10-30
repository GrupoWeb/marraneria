<?php

use Illuminate\Database\Seeder;
use App\Model\marranex\medida;

class measureSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $measure = new medida;

        $measure->nombre = "Libra";
        $measure->estado_id = 1;
        $measure->save();
    }
}
