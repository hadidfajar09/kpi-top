<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id');
            $table->string('path_foto');
            $table->string('path_foto_2')->nullable();
            $table->string('path_foto_3')->nullable();
            $table->string('path_foto_4')->nullable();
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('cod');
    }
}
