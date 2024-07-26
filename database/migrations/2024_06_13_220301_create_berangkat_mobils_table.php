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
            $table->foreignId('berangkat_timbang_id');
            $table->foreign('berangkat_timbang_id')->references('id')->on('berangkat_timbangs')->cascadeOnDelete();
            $table->foreignId('mobil_id');
            $table->foreign('mobil_id')->references('id')->on('mobils')->restrictOnDelete();
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
        Schema::dropIfExists('berangkat_mobils');
    }
}
