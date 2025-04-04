<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerangkatMobilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berangkat_mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota');
            $table->date('tanggal');
            $table->foreignId('pabrik_id');
            $table->foreign('pabrik_id')->references('id')->on('pabriks')->cascadeOnDelete();
            $table->foreignId('mobil_id');
            $table->foreign('mobil_id')->references('id')->on('mobils')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
            $table->foreignId('supir_id');
            $table->foreign('supir_id')->references('id')->on('supirs')->restrictOnDelete();
            $table->unique(['nomor_nota']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berangkat_mobils');
    }
}
