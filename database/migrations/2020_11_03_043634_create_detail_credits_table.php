<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('credits_id')->unsigned();
            $table->bigInteger('banks_id')->unsigned();
            $table->float('monto');
            $table->timestamps();

            $table->foreign('credits_id')->references('id')->on('credits');
            $table->foreign('banks_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_credits');
    }
}
