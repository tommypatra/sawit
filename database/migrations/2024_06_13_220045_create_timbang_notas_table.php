<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimbangNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbang_notas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_nota')->unique();
            $table->dateTime('waktu');
            $table->enum('jenis_bayar', ['tunai', 'transfer']);
            $table->integer('biaya_transfer')->default(0);
            $table->foreignId('sumber_bayar_id');
            $table->foreign('sumber_bayar_id')->references('id')->on('sumber_bayars')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
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
        Schema::dropIfExists('timbang_notas');
    }
}
