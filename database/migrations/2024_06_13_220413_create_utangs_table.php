<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utangs', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah');
            $table->dateTime('waktu');
            $table->string('keterangan');
            $table->enum('jenis', ['utang', 'bayar']);
            $table->foreignId('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->restrictOnDelete();
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
        Schema::dropIfExists('utangs');
    }
}
