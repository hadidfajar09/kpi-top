<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthAgentController;
use App\Http\Controllers\BriefingController;
use App\Http\Controllers\CleaningController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\GroomingController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OmsetController;
use App\Http\Controllers\PangkalanController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenempatanController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\RiwayatPengantaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CodController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WebcamController;
use App\Http\Controllers\ShiftController;
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

    //repost closing
     // //laporan
     Route::get('/closing', [ReportClosingController::class, 'index'])->name('closing.index');
     Route::get('/closing/data/{awal}/{akhir}', [ReportClosingController::class, 'data'])->name('closing.data');
     Route::get('/closing/pdf/{awal}/{akhir}', [ReportClosingController::class, 'exportPdf'])->name('closing.export');


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
      Route::post('/karyawan/reset', [KaryawanController::class, 'resetPoint'])->name('karyawan.reset');
      Route::get('/karyawan/detail/data', [KaryawanController::class, 'detail'])->name('karyawan.detail.data');
      
      //diagram
      Route::get('karyawan/grafik/{id}', [KaryawanController::class, 'grafikKaryawan'])->name('karyawan.grafik');
      //report
      Route::get('/karyawan/laporan/{id}', [KaryawanController::class, 'laporan'])->name('karyawan.laporan');
      Route::get('/karyawan/data/{awal}/{akhir}/{id}', [KaryawanController::class, 'dataReport'])->name('report.data');
      Route::get('/karyawan/pdf/{awal}/{akhir}/{id}', [KaryawanController::class, 'exportDaily'])->name('report.export');
      // Route::get('karyawan/reset', [KaryawanController::class, 'resetPoint'])->name('karyawan.reset');
    //   Route::post('/pelanggan/cetak-qrcode', [PelangganController::class, 'cetakQrcode'])->name('pelanggan.qrcode');
    //   Route::post('/pelanggan/cetak-jpg', [PelangganController::class, 'cetakJPG'])->name('pelanggan.jpg');

    //   Route::post('/pelanggan/import', [PelangganController::class, 'importExcel'])->name('pelanggan.import');


     //karyawan
     Route::get('/penempatan/data', [PenempatanController::class, 'data'])->name('penempatan.data');
     Route::resource('/penempatan',PenempatanController::class);


          // //omset
          Route::get('/omset/data', [OmsetController::class, 'data'])->name('omset.data');
          Route::resource('/omset', OmsetController::class);
          Route::get('/omset/acc/{id}', [OmsetController::class, 'accept'])->name('omset.acc');
          Route::get('/omset/decline/{id}', [OmsetController::class, 'decline'])->name('omset.decline');
          
          // //shift
          Route::get('/shift/data', [ShiftController::class, 'data'])->name('shift.data');
          Route::resource('/shift', ShiftController::class);

   // //cod
   Route::get('/cod/data', [CodController::class, 'data'])->name('cod.data');
   Route::resource('/cod', CodController::class);
   Route::post('/cod/updated/{id}', [CodController::class, 'updated'])->name('cod.updated');
   Route::get('/cod/acc/{id}', [CodController::class, 'accept'])->name('cod.acc');
   Route::get('/cod/decline/{id}', [CodController::class, 'decline'])->name('cod.decline');

   // //absen
   Route::get('/absen/masuk', [AbsensiController::class, 'masuk'])->name('absen.masuk');
   Route::post('/absen/login', [AbsensiController::class, 'absenLogin'])->name('absen.login');
   Route::get('/absen/istirahat', [AbsensiController::class, 'istirahat'])->name('absen.istirahat');
   Route::post('/absen/rest', [AbsensiController::class, 'absenRest'])->name('absen.rest');
   Route::get('/absen/pulang', [AbsensiController::class, 'pulang'])->name('absen.pulang');
   Route::post('/absen/logout', [AbsensiController::class, 'absenLogout'])->name('absen.logout');
   Route::get('/absen/data', [AbsensiController::class, 'data'])->name('absen.data');
   Route::resource('/absen', AbsensiController::class);
   Route::post('/absen/updated/{id}', [AbsensiController::class, 'updated'])->name('absen.updated');
   Route::get('/absen/acc/{id}', [AbsensiController::class, 'accept'])->name('absen.acc');
   Route::get('/absen/decline/{id}', [AbsensiController::class, 'decline'])->name('absen.decline');


            // //grooming
            Route::get('/grooming/data', [GroomingController::class, 'data'])->name('grooming.data');
            Route::resource('/grooming', GroomingController::class);
            Route::post('/grooming/updated/{id}', [GroomingController::class, 'updated'])->name('grooming.updated');
            Route::get('/grooming/acc/{id}', [GroomingController::class, 'accept'])->name('grooming.acc');
            Route::get('/grooming/decline/{id}', [GroomingController::class, 'decline'])->name('grooming.decline');
            
            // //kebersihan
            Route::get('/cleaning/data', [CleaningController::class, 'data'])->name('cleaning.data');
            Route::resource('/cleaning', CleaningController::class);
            Route::post('/cleaning/updated/{id}', [CleaningController::class, 'updated'])->name('cleaning.updated');
            Route::get('/cleaning/acc/{id}', [CleaningController::class, 'accept'])->name('cleaning.acc');
            Route::get('/cleaning/decline/{id}', [CleaningController::class, 'decline'])->name('cleaning.decline');

              // //breafing
              Route::get('/briefing/data', [BriefingController::class, 'data'])->name('briefing.data');
              Route::resource('/briefing', BriefingController::class);
              Route::post('/briefing/updated/{id}', [BriefingController::class, 'updated'])->name('briefing.updated');
              Route::get('/briefing/acc/{id}', [BriefingController::class, 'accept'])->name('briefing.acc');
            Route::get('/briefing/decline/{id}', [BriefingController::class, 'decline'])->name('briefing.decline');
  
            
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
