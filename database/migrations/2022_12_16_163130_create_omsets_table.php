<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omsets', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal_setor');
            $table->integer('user_id');
            $table->integer('karyawan_id');
            $table->text('catatan')->nullable();
            $table->string('nominal');
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
        Schema::dropIfExists('omsets');
    }
}
