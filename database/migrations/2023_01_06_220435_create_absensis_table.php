<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id')->nullable();
            $table->string('jam_masuk')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('jam_istirahat')->nullable();
            $table->string('foto_istirahat')->nullable();
            $table->string('jam_akhir')->nullable();
            $table->string('foto_akhir')->nullable();
            $table->string('jam_pulang')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('absensis');
    }
}
