<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerangkatPabriksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berangkat_pabriks', function (Blueprint $table) {
            $table->id();
            $table->integer('timbang_kotor');
            $table->integer('timbang_bersih');
            $table->integer('harga_sawit');
            $table->integer('sewa_supir');
            $table->integer('sewa_mobil');
            $table->integer('biaya_muat');
            $table->integer('biaya_bongkar');
            $table->integer('total_sawit');
            $table->integer('total_masuk');
            $table->integer('total_keluar');
            $table->date('tanggal');
            $table->foreignId('berangkat_timbang_id');
            $table->foreign('berangkat_timbang_id')->references('id')->on('berangkat_timbangs')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['berangkat_timbang_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berangkat_pabriks');
    }
}
