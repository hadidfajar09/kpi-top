<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthAgentController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PangkalanController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenempatanController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\RiwayatPengantaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WebcamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

Route::get('webcam', [WebcamController::class, 'index']);
Route::post('webcam', [WebcamController::class, 'store'])->name('webcam.capture');

Route::match(["GET", "POST"], "/register", function () { //untuk disable button route register
    return redirect('/login'); //route kembali ke form login
})->name("register");

//untuk agent simoji
Route::middleware('agent:agent')->group(function () {
    Route::get('agent/login', [AuthAgentController::class, 'loginForm']);
    Route::post('agent/login', [AuthAgentController::class, 'store'])->name('agent.login');
});


Route::middleware(['auth:sanctum,agent', config('jetstream.auth_session'), 'verified'
])->group(function () {
    Route::get('/agent/dashboard', function () {
        return view('dashboard');
    })->name('agent.dashboard')->middleware('auth:agent');
});

//end agent



//untuk admin simoji
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
    
    
// });

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    // //Pekerjaan
    Route::get('/pekerjaan/data', [PekerjaanController::class, 'data'])->name('pekerjaan.data');
    Route::resource('/pekerjaan', PekerjaanController::class);
    

    // //Penghasilan
    Route::get('/penghasilan/data', [PenghasilanController::class, 'data'])->name('penghasilan.data');
    Route::resource('/penghasilan', PenghasilanController::class);

    // //Penghasilan
    Route::get('/kecamatan/data', [KecamatanController::class, 'data'])->name('kecamatan.data');
    Route::resource('/kecamatan', KecamatanController::class);

    // //Penghasilan
    Route::get('/desa/data', [DesaController::class, 'data'])->name('desa.data');
    Route::resource('/desa', DesaController::class);

    //setting
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
    Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');

    // //pangkalan
    Route::get('/pangkalan/data', [PangkalanController::class, 'data'])->name('pangkalan.data');
    Route::resource('/pangkalan', PangkalanController::class);
    Route::get('/get/desa/{id_kecamatan}', [PangkalanController::class, 'GetDesa']);


      // //pangkalan
      Route::get('/agent/data', [AgentController::class, 'data'])->name('agent.data');
      Route::resource('/agent', AgentController::class);

      //KPI
         // //jabatan
         Route::get('/jabatan/data', [JabatanController::class, 'data'])->name('jabatan.data');
         Route::resource('/jabatan', JabatanController::class);

         // //jabatan
         Route::get('/kontrak/data', [KontrakController::class, 'data'])->name('kontrak.data');
         Route::resource('/kontrak', KontrakController::class);

            //karyawan
      Route::get('/karyawan/data', [KaryawanController::class, 'data'])->name('karyawan.data');
      Route::resource('/karyawan',KaryawanController::class);
      Route::post('/karyawan/perbarui/{id}', [KaryawanController::class, 'barui'])->name('karyawan.barui');
      Route::get('file/download/{id}', [KaryawanController::class, 'download'])->name('file.download');
    //   Route::post('/pelanggan/cetak-qrcode', [PelangganController::class, 'cetakQrcode'])->name('pelanggan.qrcode');
    //   Route::post('/pelanggan/cetak-jpg', [PelangganController::class, 'cetakJPG'])->name('pelanggan.jpg');
    //   Route::post('/pelanggan/reset', [PelangganController::class, 'resetBulanan'])->name('pelanggan.reset');

    //   Route::post('/pelanggan/import', [PelangganController::class, 'importExcel'])->name('pelanggan.import');


     //karyawan
     Route::get('/penempatan/data', [PenempatanController::class, 'data'])->name('penempatan.data');
     Route::resource('/penempatan',PenempatanController::class);


          // //omset
          Route::get('/omset/data', [KontrakController::class, 'data'])->name('kontrak.data');
          Route::resource('/omset', KontrakController::class);

         //END KPI
      //account
      Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
      Route::post('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
      Route::get('/user/profile/show', [UserController::class, 'show'])->name('profile.show');

      //pelanggan
      Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
      Route::resource('/pelanggan',PelangganController::class);
      Route::post('/pelanggan/cetak-qrcode', [PelangganController::class, 'cetakQrcode'])->name('pelanggan.qrcode');
      Route::post('/pelanggan/cetak-jpg', [PelangganController::class, 'cetakJPG'])->name('pelanggan.jpg');
      Route::post('/pelanggan/reset', [PelangganController::class, 'resetBulanan'])->name('pelanggan.reset');

      Route::post('/pelanggan/import', [PelangganController::class, 'importExcel'])->name('pelanggan.import');

       // //distribusi
    Route::get('/distribusi/data', [DistribusiController::class, 'data'])->name('distribusi.data');
    Route::resource('/distribusi', DistribusiController::class);
    Route::get('/get/pangkalan/{id_pangkalan}', [DistribusiController::class, 'GetPangkalan']);


       // //transaksi
       Route::get('/transaksi/export', [TransaksiController::class, 'transaksiExport'])->name('transaksi.export');
       Route::get('/transaksi/data', [TransaksiController::class, 'data'])->name('transaksi.data');
       Route::resource('/transaksi', TransaksiController::class);

          // //laporan
          Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
          Route::get('/transaksi/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
          Route::get('/transaksi/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPdf'])->name('laporan.export');

               // //slider/pengumuman
      Route::get('/slider/data', [SliderController::class, 'data'])->name('slider.data');
      Route::resource('/slider', SliderController::class);

       // //slider/pengumuman
       Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
       Route::get('/user/show/edit', [UserController::class, 'showUser'])->name('user.show.edit');
       Route::resource('/user', UserController::class);


      

});
