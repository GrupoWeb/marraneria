<?php

use Illuminate\Database\Seeder;
use App\Model\marranex\bank;

class bankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = new bank;

        $bank->name = 'GyT Continental';
        $bank->save();
    }
}
