<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('jabatan_id');
            $table->integer('kontrak_id');
            $table->integer('penempatan_id');
            $table->string('berkas');
            $table->string('alamat');
            $table->string('nomor');
            $table->string('join_date');
            $table->string('end_work');
            $table->string('foto')->nullable();
            $table->string('path_berkas')->nullable();
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
        Schema::dropIfExists('karyawans');
    }
}
