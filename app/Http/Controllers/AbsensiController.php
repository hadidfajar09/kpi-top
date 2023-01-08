<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\karyawan;
use App\Models\User;
use App\Models\Shift;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.absensi.index');
    }

    public function data()
    {
        $absensi = Absensi::
            latest()->get();

            $absensi_karyawan = Absensi::where('karyawan_id', auth()->user()->id)->latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($absensi)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('tanggal', function($absensi){
                    $result = formatTanggal($absensi->created_at);
                    return $result;
                })
                ->addColumn('karyawan', function($absensi){
                    $result = '<h1 class="badge badge-light">'.$absensi->karyawan->name.'</h1>';
                    return $result;
                })
                ->addColumn('jam_masuk', function($absensi){
                    $result = '<h1 class="badge badge-success">'.$absensi->jam_masuk.'</h1>';
                    return $result;
                })
                ->addColumn('foto_masuk', function($absensi){
                    return ' <a href="'.$absensi->foto_masuk.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi->foto_masuk.'" class="img-fluid" alt="">
                  </a>';
                })
                ->addColumn('jam_istirahat', function($absensi){
                    $result = '<h1 class="badge badge-info">'.$absensi->jam_istirahat.'</h1>';
                    return $result;
                })
                ->addColumn('foto_istirahat', function($absensi){
                    return ' <a href="'.$absensi->foto_istirahat.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi->foto_istirahat.'" class="img-fluid" alt="">
                  </a>';
                })
                ->addColumn('jam_pulang', function($absensi){
                    $result = '<h1 class="badge badge-danger">'.$absensi->jam_pulang.'</h1>';
                    return $result;
                })
                ->addColumn('foto_pulang', function($absensi){
                    return ' <a href="'.$absensi->foto_pulang.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi->foto_pulang.'" class="img-fluid" alt="">
                  </a>';
                })
                ->addColumn('accept', function($absensi){
                    if($absensi->accept == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($absensi->accept == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
               


                ->addColumn('status', function($absensi){
                    if($absensi->status == 0){
                        return '<span class="badge badge-warning">Sakit</span>';
      
                    }else if($absensi->status == 1){
                      return '<span class="badge badge-success">Hadir</span>';
                    }else if($absensi->status == 3){
                        return '<span class="badge badge-warning">Izin</span>';
                    }else{
                        return '<span class="badge badge-danger">Telat</span>';

                    }
                })
             
                ->addColumn('aksi', function($absensi){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('absen.update', $absensi->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('absen.destroy', $absensi->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('absen.acc', $absensi->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('absen.decline', $absensi->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','karyawan','jam_masuk','foto_masuk','jam_istirahat','foto_istirahat','jam_pulang','foto_pulang','tanggal','status','accept'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($absensi_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('tanggal', function($absensi_karyawan){
                    $result = formatTanggal($absensi_karyawan->created_at);
                    return $result;
                })
                ->addColumn('karyawan', function($absensi_karyawan){
                    $result = '<h1 class="badge badge-light">'.$absensi_karyawan->karyawan->name.'</h1>';
                    return $result;
                })
                ->addColumn('jam_masuk', function($absensi_karyawan){
                    $result = '<h1 class="badge badge-success">'.$absensi_karyawan->jam_masuk.'</h1>';
                    return $result;
                })
                ->addColumn('foto_masuk', function($absensi_karyawan){
                    return ' <a href="'.$absensi_karyawan->foto_masuk.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi_karyawan->foto_masuk.'" class="img-fluid" alt="">
                  </a>';
                })
                ->addColumn('jam_istirahat', function($absensi_karyawan){
                    $result = '<h1 class="badge badge-info">'.$absensi_karyawan->jam_istirahat.'</h1>';
                    return $result;
                })
                ->addColumn('foto_istirahat', function($absensi_karyawan){
                    return ' <a href="'.$absensi_karyawan->foto_istirahat.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi_karyawan->foto_istirahat.'" class="img-fluid" alt="">
                  </a>';
                })
                ->addColumn('jam_pulang', function($absensi_karyawan){
                    $result = '<h1 class="badge badge-danger">'.$absensi_karyawan->jam_pulang.'</h1>';
                    return $result;
                })
                ->addColumn('foto_pulang', function($absensi_karyawan){
                    return ' <a href="'.$absensi_karyawan->foto_pulang.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$absensi_karyawan->foto_pulang.'" class="img-fluid" alt="">
                  </a>';
                })
             
                ->addColumn('accept', function($absensi_karyawan){
                    if($absensi_karyawan->accept == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($absensi_karyawan->accept == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{  
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })


                ->addColumn('status', function($absensi_karyawan){
                    if($absensi_karyawan->status == 0){
                        return '<span class="badge badge-warning">Sakit</span>';
      
                    }else if($absensi_karyawan->status == 1){
                      return '<span class="badge badge-success">Hadir</span>';
                    }else if($absensi_karyawan->status == 3){
                        return '<span class="badge badge-warning">Izin</span>';
                    }else{
                        return '<span class="badge badge-danger">Telat</span>';

                    }
                })
             
                ->addColumn('aksi', function($absensi_karyawan){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('absen.update', $absensi_karyawan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button></div>';
                   return $button;
                })
                ->rawColumns(['aksi','karyawan','jam_masuk','foto_masuk','jam_istirahat','foto_istirahat','jam_pulang','foto_pulang','tanggal','status','accept'])//biar kebaca html
                ->make(true);
            }
        
    }

    public function masuk()
    {
        return view('backend.absensi.create_masuk');
    }

    public function absenLogin(Request $request)
    {
        $karyawan = auth()->user()->id;
        $data_lama = Absensi::where('karyawan_id',$karyawan)->latest()->first();
        $data_shift = karyawan::where('id',auth()->user()->karyawan_id)->first();
        $shift = Shift::where('id',$data_shift->shift_id)->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') < date('Y-m-d')){
                $masuk = new Absensi();

                if($shift->masuk >= date('H:i')){
                    $masuk->status = 1; //hadir
                }else{
                    $masuk->status = 2; //telat
                }
                $masuk->karyawan_id = $karyawan;
                $masuk->jam_masuk = date('H:i');
        
                if($request->foto_masuk == NULL){
                    $notif = array(
                        'message' => 'Anda belum memasukkan foto',
                        'alert-type' => 'error'
                    );
        
                    return redirect()->back()->with($notif);
                }else{
                    $img = $request->foto_masuk;
                    $folderPath = "masuk/";
                    
                    $image_parts = explode(";base64,", $img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = uniqid() . '.png';
                    
                    $file = $folderPath . $fileName;
                    
                    Storage::disk('public_uploads')->put($file, $image_base64);
        
                    $masuk->foto_masuk = 'uploads/masuk/'.$fileName;
                    $masuk->save();
                }
                  
                $notif = array(
                    'message' => 'Data Absen Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('absen.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Data Absen sudah ada',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
            $masuk = new Absensi();

            if($shift->masuk >= date('H:i')){
                $masuk->status = 1; //hadir
            }else{
                $masuk->status = 2; //telat
            }
            $masuk->karyawan_id = $karyawan;
            $masuk->jam_masuk = date('H:i');
    
            if($request->foto_masuk == NULL){
                $notif = array(
                    'message' => 'Anda belum memasukkan foto',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }else{
                $img = $request->foto_masuk;
                $folderPath = "masuk/";
                
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = uniqid() . '.png';
                
                $file = $folderPath . $fileName;
                
                Storage::disk('public_uploads')->put($file, $image_base64);
    
                $masuk->foto_masuk = 'uploads/masuk/'.$fileName;
                $masuk->save();
            }
              
            $notif = array(
                'message' => 'Data Absen Berhasil di Upload',
                'alert-type' => 'success'
            );
    
            return redirect()->route('absen.index')->with($notif);
        }
    }

    public function istirahat()
    {
        return view('backend.absensi.create_istirahat');
    }

    public function absenRest(Request $request)
    {
        $karyawan = auth()->user()->id;
        $data_lama = Absensi::where('karyawan_id',$karyawan)->latest()->first();
        $data_shift = karyawan::where('id',auth()->user()->karyawan_id)->first();
        $shift = Shift::where('id',$data_shift->shift_id)->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') == date('Y-m-d')){
               
                $data_lama->jam_istirahat = date('H:i');
        
                if($request->foto_istirahat == NULL){
                    $notif = array(
                        'message' => 'Anda belum memasukkan foto',
                        'alert-type' => 'error'
                    );
        
                    return redirect()->back()->with($notif);
                }else{
                    if($data_lama->foto_masuk){
                        $img = $request->foto_istirahat;
                        $folderPath = "istirahat/";
                        
                        $image_parts = explode(";base64,", $img);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        
                        $image_base64 = base64_decode($image_parts[1]);
                        $fileName = uniqid() . '.png';
                        
                        $file = $folderPath . $fileName;
                        
                        Storage::disk('public_uploads')->put($file, $image_base64);
            
                        $data_lama->foto_istirahat = 'uploads/istirahat/'.$fileName;
                        $data_lama->update();
                    }else{
                        $notif = array(
                            'message' => 'Data Absen Masuk Belum ada',
                            'alert-type' => 'error'
                        );

                        return redirect()->back()->with($notif);
                    }
                   
                }
                  
                $notif = array(
                    'message' => 'Data Istirahat Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('absen.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Anda Belum Absen Masuk',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
              
            $notif = array(
                'message' => 'Anda Belum Absen Hari ini',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notif);
        }
    }

    public function pulang()
    {
        return view('backend.absensi.create_pulang');
    }

    public function absenLogout(Request $request)
    {
        $karyawan = auth()->user()->id;
        $data_lama = Absensi::where('karyawan_id',$karyawan)->latest()->first();
        $data_shift = karyawan::where('id',auth()->user()->karyawan_id)->first();
        $shift = Shift::where('id',$data_shift->shift_id)->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') == date('Y-m-d')){
               
                $data_lama->jam_pulang = date('H:i');
        
                if($request->foto_pulang == NULL){
                    $notif = array(
                        'message' => 'Anda belum memasukkan foto',
                        'alert-type' => 'error'
                    );
        
                    return redirect()->back()->with($notif);
                }else{
                    if($data_lama->foto_istirahat){
                        $img = $request->foto_pulang;
                        $folderPath = "pulang/";
                        
                        $image_parts = explode(";base64,", $img);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        
                        $image_base64 = base64_decode($image_parts[1]);
                        $fileName = uniqid() . '.png';
                        
                        $file = $folderPath . $fileName;
                        
                        Storage::disk('public_uploads')->put($file, $image_base64);
            
                        $data_lama->foto_pulang = 'uploads/pulang/'.$fileName;
                        $data_lama->update();
                    }else{
                        $notif = array(
                            'message' => 'Data Absen Istirahat Belum ada',
                            'alert-type' => 'error'
                        );

                        return redirect()->back()->with($notif);
                    }
                   
                }
                  
                $notif = array(
                    'message' => 'Data Pulang Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('absen.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Anda Belum Absen Hari Ini',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
              
            $notif = array(
                'message' => 'Anda Belum Absen Hari ini',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notif);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function accept($id)
    {
        $absen = Absensi::findOrFail($id);
        $user = User::where('id',$absen->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($absen->accept == 0){ //ditolak
            $data_karyawan->absen += 1;
            $data_karyawan->update();   
        }
        if($absen->accept == 2){ //pending
            $data_karyawan->absen += 1;
            $data_karyawan->update();
        }else{

        }
        Absensi::findOrFail($id)->update([
            'accept' => 1
        ]);

        $notif = array(
            'message' => 'Data Absen Diterima',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);

    }

    public function decline($id)
    {
        $absen = Absensi::findOrFail($id);
        $user = User::where('id',$absen->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($absen->status == 1){ //diterima
            $data_karyawan->absen--;
            $data_karyawan->update();   
        }
        if($absen->status == 2){ //pending
            $data_karyawan->absen--;
            $data_karyawan->update();
        }else{

        }

        Absensi::findOrFail($id)->update([
            'accept' => 0
        ]);

        $notif = array(
            'message' => 'Data Absen Ditolak',
            'alert-type' => 'error'
        );

      
       return redirect()->back()->with($notif);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absen = Absensi::find($id);
        return response()->json($absen);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::find($id);
        $absensi->status = $request->status;
        $absensi->keterangan = $request->keterangan;

        $absensi->update();

        return response()->json('Status Kehadiran berhasil diubah', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
