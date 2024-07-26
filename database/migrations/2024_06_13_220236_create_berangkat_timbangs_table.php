<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerangkatTimbangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berangkat_timbangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota');
            $table->integer('timbang_kotor');
            $table->integer('timbang_bersih');
            $table->date('tanggal');
            $table->foreignId('pabrik_id');
            $table->foreign('pabrik_id')->references('id')->on('pabriks')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['nomor_nota']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berangkat_timbangs');
    }
}
