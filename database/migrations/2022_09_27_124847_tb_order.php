<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_order', function (Blueprint $table) {
            $table->increments('id_order');
            $table->integer('id_pelanggan')->unsigned(); 
            $table->foreign('id_pelanggan')->references('id')->on('users');
            $table->string('jrawat');
            $table->string('keluhan');
            $table->integer('status'); 
            $table->dateTime('create_pending');
            $table->dateTime('create_proses')->nullable();
            $table->dateTime('create_selesai')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_order');
    }
}
