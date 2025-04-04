<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerawatanMobilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perawatan_mobils', function (Blueprint $table) {
            $table->id();
            $table->string('item')->nullable();
            $table->integer('biaya')->default(0);
            $table->date('tanggal');
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
        Schema::dropIfExists('perawatan_mobils');
    }
}
