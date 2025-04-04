<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbmMobilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbm_mobils', function (Blueprint $table) {
            $table->integer('liter')->nullable();
            $table->integer('harga_satuan')->default(0);
            $table->integer('total')->default(0);
            $table->date('tanggal');
            $table->foreignId('mobil_id');
            $table->foreign('mobil_id')->references('id')->on('mobils')->restrictOnDelete();
            $table->foreignId('supir_id');
            $table->foreign('supir_id')->references('id')->on('supirs')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bbm_mobils');
    }
}
