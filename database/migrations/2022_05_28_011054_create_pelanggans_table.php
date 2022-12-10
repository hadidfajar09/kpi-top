<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pelanggan');
            $table->string('kode_pelanggan');
            $table->string('alamat');
            $table->integer('jumlah_tabung');
            $table->integer('jatah_bulanan');
            $table->bigInteger('nik')->unique();
            $table->bigInteger('no_kk');
            $table->string('qrcode');
            $table->integer('id_pangkalan');
            $table->integer('id_pekerjaan');
            $table->integer('id_penghasilan');
            $table->integer('id_kecamatan');
            $table->integer('id_desa');
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
        Schema::dropIfExists('pelanggans');
    }
}
