<?php

use Illuminate\Database\Seeder;
use App\Model\marranex\estados;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new estados;

        $data->name = 'Activo';
        $data->save();

        $data = new estados;
        $data->name = 'Inactivo';
        $data->save();

        $data->name = 'Credito';
        $data->save();

        $data = new estados;
        $data->name = 'Contado';
        $data->save();

        $data = new estados;
        $data->name = 'Pagado';
        $data->save();
    }
}
