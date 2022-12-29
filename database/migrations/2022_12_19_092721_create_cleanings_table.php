<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCleaningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cleanings', function (Blueprint $table) {
            $table->id();
            $table->integer('penempatan_id');
            $table->integer('karyawan_id');
            $table->integer('user_id');
            $table->string('path_foto');
            $table->string('path_foto_2')->nullable();
            $table->string('path_foto_3')->nullable();
            $table->string('path_foto_4')->nullable();
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('cleanings');
    }
}
