<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimbangBelisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbang_belis', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_satuan');
            $table->integer('harga_satuan');
            $table->integer('total_bayar');

            $table->foreignId('timbang_nota_id');
            $table->foreign('timbang_nota_id')->references('id')->on('timbang_notas')->cascadeOnDelete();

            $table->foreignId('timbang_tiket_id');
            $table->foreign('timbang_tiket_id')->references('id')->on('timbang_tikets')->restrictOnDelete();

            $table->unique(['timbang_tiket_id']);
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
        Schema::dropIfExists('timbang_belis');
    }
}
