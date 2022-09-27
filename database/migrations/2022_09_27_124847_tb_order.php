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
            $table->foreign('id_pelanggan')->references('id_profile')->on('tb_profile');
            $table->string('sakit');
            $table->string('keluhan');
            $table->date('tgllahir');
            $table->integer('status'); 
            $table->date('create_pending');
            $table->date('create_proses');
            $table->date('create_selesai');
           
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
