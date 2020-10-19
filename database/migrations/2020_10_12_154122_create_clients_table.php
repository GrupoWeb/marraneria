<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nit',255)->nullable();
            $table->string('name',255);
            $table->string('surname',255);
            $table->string('address',255)->nullable();
            $table->string('dpi',255)->nullable();
            $table->string('phone',255)->nullable();
            $table->string('company',255)->nullable();
            $table->string('discount',255)->nullable();
            $table->bigInteger('status_id')->unsigned();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
