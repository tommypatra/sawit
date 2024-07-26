<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerangkatSupirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berangkat_supirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berangkat_mobil_id');
            $table->foreign('berangkat_mobil_id')->references('id')->on('berangkat_mobils')->cascadeOnDelete();
            $table->foreignId('supir_id');
            $table->foreign('supir_id')->references('id')->on('supirs')->restrictOnDelete();
            $table->foreignId('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators')->restrictOnDelete();
            // $table->unique(['berangkat_mobil_id', 'supir_id']);
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
        Schema::dropIfExists('berangkat_sopirs');
    }
}
