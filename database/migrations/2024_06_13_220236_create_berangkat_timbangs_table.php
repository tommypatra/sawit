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
            $table->integer('ram_timbang_kotor');
            $table->integer('ram_timbang_bersih');
            $table->integer('pabrik_timbang_kotor')->nullable();
            $table->integer('pabrik_timbang_bersih')->nullable();
            $table->integer('harga')->nullable();
            $table->integer('nilai_susut')->nullable();
            $table->decimal('persen', 10, 2)->nullable();
            $table->foreignId('ram_id');
            $table->foreign('ram_id')->references('id')->on('rams')->restrictOnDelete();
            // $table->foreignId('pabrik_id');
            // $table->foreign('pabrik_id')->references('id')->on('pabriks')->restrictOnDelete();
            $table->foreignId('berangkat_mobil_id');
            $table->foreign('berangkat_mobil_id')->references('id')->on('berangkat_mobils')->restrictOnDelete();
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
        Schema::dropIfExists('berangkat_timbangs');
    }
}
