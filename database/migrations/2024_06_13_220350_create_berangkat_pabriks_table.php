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
            $table->date('tanggal_timbang')->nullable();
            $table->decimal('tp', 10, 2)->nullable();
            $table->integer('timbang_kotor')->nullable();
            $table->integer('timbang_bersih')->nullable();
            $table->integer('nilai_susut')->nullable();
            $table->integer('harga_sawit')->nullable();
            $table->integer('sewa_mobil')->nullable();
            $table->integer('biaya_loading')->nullable();
            $table->integer('biaya_bongkar')->nullable();
            $table->integer('bersih')->nullable();
            $table->foreignId('berangkat_mobil_id');
            $table->foreign('berangkat_mobil_id')->references('id')->on('berangkat_mobils')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['berangkat_mobil_id']);
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
