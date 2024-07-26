<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimbangTiketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timbang_tikets', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable();
            $table->float('timbang_bersih')->default(0);
            $table->date('tanggal');
            $table->foreignId('pelanggan_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->restrictOnDelete();
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
        Schema::dropIfExists('timbang_tikets');
    }
}
