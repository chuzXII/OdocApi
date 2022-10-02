<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbCacheOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cache_order', function (Blueprint $table) {
            $table->increments('id_order');
            $table->bigInteger('id_pelanggan'); 
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
        //
    }
}
