<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\grooming;
use App\Models\Cod;
use App\Models\Omset;
use App\Models\Cleaning;
use App\Models\Briefing;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\kontrak;
use App\Models\karyawan;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Shift;
use Carbon\Carbon;
use App\Models\Penempatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.karyawan.index');
    }

    public function data()
    {
        $karyawan = karyawan::leftJoin('jabatans', 'jabatans.id', 'karyawans.jabatan_id')
            ->leftJoin('kontraks', 'kontraks.id', 'karyawans.kontrak_id')
            ->leftJoin('penempatans', 'penempatans.id', 'karyawans.penempatan_id')
            ->select('karyawans.*','jabatan','kontrak','nama')
            ->orderBy('name','asc')->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($karyawan)//source
                ->addIndexColumn() //untuk nomer
             
                ->addColumn('berkas', function($karyawan){
                    if($karyawan->berkas){
                        return $karyawan->berkas;
                    }else{
                        return '<span class="badge badge-dark">Belum Terisi</span>';
                    }
                })
                ->addColumn('end_work', function($karyawan){
                    if($karyawan->end_work){
                        return formatTanggal($karyawan->end_work);
                    }else{
                        return '<span class="badge badge-dark">Belum Terisi</span>';
                    }
                })

                ->addColumn('status', function($karyawan){
                    if($karyawan->end_work <= now()){
                        return '<span class="badge badge-danger">NonAKtif</span>';
      
                    }else{
                      return '<span class="badge badge-success">Aktif</span>';
                    }
                })

                ->addColumn('foto', function($karyawan){
                    return ' <a href="'.$karyawan->foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$karyawan->foto.'" class="img-fluid" alt="" width="100">
                  </a>';    
                })
             
                ->addColumn('aksi', function($karyawan){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('karyawan.edit', $karyawan->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('karyawan.destroy', $karyawan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> <a href="'.route('file.download', $karyawan->id).'" class="btn btn-xs btn-warning btn-flat" target="_blank"><i class="fa fa-download"></i></a> <a href="'.route('karyawan.show', $karyawan->id).'" class="btn btn-xs btn-success btn-flat" target="_blank"><i class="fas fa-chart-pie"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','end_work','status','foto','berkas'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($karyawan){
                    return '<input type="checkbox" name="id_pelanggan[]" value="'.$karyawan->id.'">';
                })
                ->addColumn('kode_pelanggan', function($karyawan){
                    return '<span class="badge badge-success">'.$karyawan->kode_pelanggan.'</span>';
                })
                ->addColumn('nominal_gaji', function($karyawan){
                    return 'Rp ' . formatUang($karyawan->penghasilan->nominal_gaji);
                })
                ->addColumn('aksi', function($karyawan){ //untuk aksi
                    $button = '-';
                   return $button;
                })
                ->rawColumns(['aksi','kode_pelanggan','select_all'])//biar kebaca html
                ->make(true);
            }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatan = Jabatan::all()->pluck('jabatan','id');
        $kontrak = kontrak::all()->pluck('kontrak','id');
        $penempatan = Penempatan::all()->pluck('nama','id');
        $shift = Shift::all()->pluck('nama_shift','id');
        return view('backend.karyawan.create', compact('jabatan','kontrak','penempatan','shift'));
    }

    public function download($id)
    {
        $karyawan = karyawan::findOrFail($id);
        $filePath = $karyawan->path_berkas;
        return response()->download($filePath);
    }

    public function detail()
    {
        $user = User::where('karyawan_id',6)->first();
        $karyawan_detail = Absensi::where('karyawan_id',$user->id)->latest()->get();

        return datatables()
        ->of($karyawan_detail)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('tanggal', function($karyawan_detail){
            $result = formatTanggal($karyawan_detail->created_at);
            return $result;
        })
        ->addColumn('karyawan', function($karyawan_detail){
            $result = '<h1 class="badge badge-light">'.$karyawan_detail->karyawan->name.'</h1>';
            return $result;
        })
        ->addColumn('jam_masuk', function($karyawan_detail){
            $result = '<h1 class="badge badge-success">'.$karyawan_detail->jam_masuk.'</h1>';
            return $result;
        })
        ->addColumn('foto_masuk', function($karyawan_detail){
            return ' <a href="'.$karyawan_detail->foto_masuk.'" data-toggle="lightbox">
            <img src="'.$karyawan_detail->foto_masuk.'" class="img-fluid" alt="">
          </a>';
        })
        ->addColumn('jam_istirahat', function($karyawan_detail){
            $result = '<h1 class="badge badge-info">'.$karyawan_detail->jam_istirahat.'</h1>';
            return $result;
        })
        ->addColumn('foto_istirahat', function($karyawan_detail){
            return ' <a href="'.$karyawan_detail->foto_istirahat.'" data-toggle="lightbox">
            <img src="'.$karyawan_detail->foto_istirahat.'" class="img-fluid" alt="">
          </a>';
        })
        ->addColumn('jam_pulang', function($karyawan_detail){
            $result = '<h1 class="badge badge-danger">'.$karyawan_detail->jam_pulang.'</h1>';
            return $result;
        })
        ->addColumn('foto_pulang', function($karyawan_detail){
            return ' <a href="'.$karyawan_detail->foto_pulang.'" data-toggle="lightbox">
            <img src="'.$karyawan_detail->foto_pulang.'" class="img-fluid" alt="">
          </a>';
        })
     
        ->addColumn('accept', function($karyawan_detail){
            if($karyawan_detail->accept == 0){
                return '<span class="badge badge-danger">Ditolak</span>';

            }else if($karyawan_detail->accept == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else{  
                return '<span class="badge badge-light">Pending</span>';

            }
        })


        ->addColumn('status', function($karyawan_detail){
            if($karyawan_detail->status == 0){
                return '<span class="badge badge-warning">Sakit</span>';

            }else if($karyawan_detail->status == 1){
              return '<span class="badge badge-success">Hadir</span>';
            }else if($karyawan_detail->status == 3){
                return '<span class="badge badge-warning">Izin</span>';
            }else{
                return '<span class="badge badge-danger">Telat</span>';

            }
        })
     
        ->addColumn('aksi', function($karyawan_detail){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('absen.update', $karyawan_detail->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button></div>';
           return $button;
        })
        ->rawColumns(['aksi','karyawan','jam_masuk','foto_masuk','jam_istirahat','foto_istirahat','jam_pulang','foto_pulang','tanggal','status','accept'])//biar kebaca html
        ->make(true);
    
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'jabatan_id' => 'required',
            'kontrak_id' => 'required',
            'penempatan_id' => 'required',
            'shift_id' => 'required',
            'berkas' => 'sometimes',
            'alamat' => 'required',
            'nomor' => 'required',
            'join_date' => 'required',
            'end_work' => 'sometimes',
            'path_berkas' => "sometimes|nullable|mimes:pdf|max:4000",
            'foto' => "sometimes|nullable|mimes:jpg,png,jpeg|max:4000"
        ]);

        $karyawan = new karyawan();

        $karyawan->name = $request->name;
        $karyawan->jabatan_id = $request->jabatan_id;
        $karyawan->kontrak_id = $request->kontrak_id;
        $karyawan->penempatan_id = $request->penempatan_id;
        $karyawan->shift_id = $request->shift_id;
        $karyawan->berkas = $request->berkas;
        $karyawan->alamat = $request->alamat;
        $karyawan->nomor = $request->nomor;
        $karyawan->join_date = $request->join_date;
        $karyawan->end_work = $request->end_work;

        if ($request->hasFile('path_berkas')) {
            if ($request->file('path_berkas')->isValid()) {
                $documentFile = $request->file('path_berkas');
                $extention = $documentFile->getClientOriginalExtension();
                $slug = \Str::slug($request->get('name'));
                $fileName = date('YmdHis') . "-" . $slug . "." . $extention;
                $request->file('path_berkas')->move(public_path('/berkas/'),$fileName);

                $karyawan->path_berkas = 'berkas/'.$fileName;
            }
        }

        if($request->hasFile('foto')){
            $image = $request->file('foto');
            $nama = 'foto-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/foto/'),$nama);

            $karyawan->foto = 'foto/'.$nama;
        }else{
            $karyawan->foto = 'img/avatar.png';
        }

        $karyawan->save();


        $karyawan_id = karyawan::latest()->first()->id;
        $request->kode_user = 'U'. tambahNolDepan((int)$karyawan_id+1, 3);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->kode_user = $request->kode_user;
        $user->karyawan_id = $karyawan_id;
        $user->password = bcrypt($request->password);
        $user->level = 5;
        $user->profile_photo_path = 'img/avatar.png';

        $user->save();

        $notif = array(
            'message' => 'Anda Berhasil Menambahkan Karyawan',
            'alert-type' => 'success'
        );

        return redirect()->route('karyawan.index')->with($notif);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $karyawan = karyawan::findOrFail($id);
        $user = User::where('karyawan_id',$id)->first();
        $karyawan_detail = Absensi::where('karyawan_id',$user->id)->latest()->paginate(30);
        $shift = Shift::where('id',$karyawan->shift_id)->first();
        return view('backend.karyawan.detail_view',compact('karyawan_detail','user','karyawan','shift'));
    }

    public function grafikKaryawan($id)//karyawan
    {
        // $jumlah_hari = Carbon::now()->diffInDays(Carbon::now()->firstOfMonth())+1;//jumlah hari
        
        //pie
        $karyawan = karyawan::find($id);
            $user = User::where('karyawan_id',$id)->first();

            //absensi
            $absensi=Absensi::where('karyawan_id',$user->id)->count();

            $absensi_diterima = Absensi::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',1)->count(); //absen yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $absensi_telat = Absensi::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $absensi_izin = Absensi::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',3)->count(); //absen yg izin
            // $absensi_izin_persentase = round($absensi_izin/$jumlah_hari*100);
            
            $absensi_sakit = Absensi::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;

            //grooming
            $grooming=grooming::where('karyawan_id',$user->id)->count();

            $grooming_diterima = grooming::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',1)->count(); //grooming yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $grooming_pending = grooming::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $grooming_ditolak = grooming::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;


            //Briefing
            $briefing=Briefing::where('user_id',$user->id)->count();

            $briefing_diterima = Briefing::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',1)->count(); //grooming yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $briefing_pending = Briefing::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $briefing_ditolak = Briefing::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;

            //Cleaning
            $cleaning=Cleaning::where('user_id',$user->id)->count();

            $cleaning_diterima = Cleaning::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',1)->count(); //grooming yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $cleaning_pending = Cleaning::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $cleaning_ditolak = Cleaning::whereMonth('created_at', Carbon::now()->month)->where('user_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;

              //Cleaning
            $cod=Cod::where('karyawan_id',$user->id)->count();

            $cod_diterima = Cod::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',1)->count(); //grooming yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $cod_pending = Cod::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $cod_ditolak = Cod::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;

            //Cleaning
            $omset=Omset::where('karyawan_id',$user->id)->count();

            $omset_diterima = Omset::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',1)->count(); //grooming yg diterima
            // $absensi_diterima_persentase = round($absensi_diterima/$jumlah_hari*100);

            $omset_pending = Omset::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',2)->count(); //absen yg telat
            // $absensi_telat_persentase = round($absensi_telat/$jumlah_hari*100);

            $omset_ditolak = Omset::whereMonth('created_at', Carbon::now()->month)->where('karyawan_id',$user->id)->where('status',0)->count(); //absen yg sakit
            // $absensi_sakit_persentase = round($absensi_sakit/$jumlah_hari*100) ;
   



            
            $tanggal_awal = date('Y-m-01');
            $tanggal_akhir = date('Y-m-d');
            
            $data_tanggal = array();
            $stock = 0;
            $total_stock = array();
    
            //stock masuk
            $stock_masuk = 0;
            $total_stock_masuk = array();
    
            //stock keluar
            $stock_keluar = 0;
            $total_stock_keluar = array();
            
            //cleaning
            $cleaning = 0;
            $total_cleaning = array();
            //Absen
            $absen = 0;
            $total_absen = array();
    
            while(strtotime($tanggal_awal) <= strtotime($tanggal_akhir)){
                $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);
    
                $distribusi = grooming::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $transaksi = Briefing::where('user_id',$user->id)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $cleaning = Cleaning::where('user_id',$user->id)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $absen = Absensi::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
    
                $stock = $distribusi - $transaksi;
                $total_stock[] += $stock;
    
                $stock_masuk = $distribusi;
                $total_stock_masuk[] += $stock_masuk;
    
                $stock_keluar = $transaksi;
                $total_stock_keluar[] += $stock_keluar;
               
                $cleaning = $cleaning;
                $total_cleaning[] += $cleaning;

                $absen = $absen;
                $total_absen[] += $absen;
                
                $tanggal_awal = date('Y-m-d', strtotime("+1 day",strtotime($tanggal_awal)));
    
            }
    
            $tanggal_awal = date('Y-m-01');
            
                return view('backend.karyawan.grafik',compact('tanggal_awal','tanggal_akhir','data_tanggal','total_stock','total_stock_masuk','total_stock_keluar','cleaning','total_cleaning','total_absen','absensi','absensi_diterima','absensi_telat','absensi_izin','absensi_sakit','grooming_diterima','grooming_ditolak','grooming_pending','karyawan','briefing_diterima','briefing_ditolak','briefing_pending','cleaning_diterima','cleaning_ditolak','cleaning_pending','cod_diterima','cod_ditolak','cod_pending','omset_diterima','omset_ditolak','omset_pending'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $karyawan = karyawan::findOrfail($id);
        $jabatan = Jabatan::all();
        $kontrak = kontrak::all();
        $penempatan = Penempatan::all();
        $shift = Shift::all();

        return view('backend.karyawan.edit',compact('jabatan','kontrak','penempatan','karyawan','shift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    public function barui(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'jabatan_id' => 'required',
            'kontrak_id' => 'required',
            'penempatan_id' => 'required',
            'shift_id' => 'required',
            'berkas' => 'sometimes',
            'alamat' => 'required',
            'nomor' => 'required',
            'join_date' => 'required',
            'end_work' => 'sometimes',
            'path_berkas' => "sometimes|nullable|mimes:pdf|max:4000",
            'foto' => "sometimes|nullable|mimes:jpg,png,jpeg|max:4000"
        ]);


        $karyawan = karyawan::find($id);

        $karyawan->name = $request->name;
        $karyawan->jabatan_id = $request->jabatan_id;
        $karyawan->kontrak_id = $request->kontrak_id;
        $karyawan->penempatan_id = $request->penempatan_id;
        $karyawan->shift_id = $request->shift_id;
        $karyawan->berkas = $request->berkas;
        $karyawan->alamat = $request->alamat;
        $karyawan->nomor = $request->nomor;
        $karyawan->join_date = $request->join_date;
        $karyawan->end_work = $request->end_work;

        if ($request->hasFile('path_berkas')) {
            if ($request->file  ('path_berkas')->isValid()) {
                $documentFile = $request->file('path_berkas');
                $extention = $documentFile->getClientOriginalExtension();
                $slug = \Str::slug($request->get('name'));
                $fileName = date('YmdHis') . "-" . $slug . "." . $extention;
                $request->file('path_berkas')->move(public_path('/berkas/'),$fileName);

                $karyawan->path_berkas = 'berkas/'.$fileName;
            }
        }

        if($request->hasFile('foto')){
            $old = $karyawan->foto;

            if($old != 'img/avatar.png'){
                unlink($old);
            }
            $image = $request->file('foto');
            $nama = 'foto-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/foto/'),$nama);

            $karyawan->foto = 'foto/'.$nama;
        }

        $karyawan->update();

        $notif = array(
            'message' => 'Anda Berhasil Update Data Karyawan',
            'alert-type' => 'success'
        );

        return redirect()->route('karyawan.index')->with($notif);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $karyawan = karyawan::find($id);

        $akun_user = User::where('karyawan_id',$id)->delete();
        if($karyawan->foto != 'img/avatar.png'){

            unlink($karyawan->foto);
        }
        if($karyawan->path_berkas){

            File::delete($karyawan->path_berkas);
        }

        $karyawan->delete();

        return response()->json('data berhasil dihapus');
    }

    public function resetPoint(Request $request)
    {
        $karyawan = karyawan::orderBy('id','desc')->get();
        foreach ($karyawan as $row) {
            $row->absen = 0;
            $row->omset = 0;
            $row->grooming = 0;
            $row->kebersihan = 0;
            $row->point = 0;
            $row->cod = 0;
            $row->briefing = 0;
            
            $row->update();
        }


        return response()->json('Point Bulanan Berhasil direset');
    }

    public function laporan(Request $request, $id)
    {
        $id = karyawan::findOrFail($id);
        $tanggalAwal = date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if($request->tanggal_awal){
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        return view('backend.karyawan.laporan.index', compact('tanggalAwal','tanggalAkhir','id'));
    }

    public function getData($awal, $akhir, $id)
    {
        $user = User::where('karyawan_id',$id)->first();//user_id
        $karyawan = karyawan::where('id',$id)->first();
       $no = 1;
       $data = array();
      

       while(strtotime($awal) <= strtotime($akhir)){
           $tanggal = $awal;

           $awal = date('Y-m-d', strtotime("+1 day",strtotime($awal)));

           $absen = Absensi::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();
           $grooming = grooming::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();
           $cleaning = Cleaning::where('user_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();
           $briefing = Briefing::where('user_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();
           $omset = Omset::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();
           $cod = Cod::where('karyawan_id',$user->id)->where('created_at', 'LIKE', "%$tanggal%")->first();

           $absen_baru = $absen['accept'] ?? 4;
           $grooming_baru = $grooming['status'] ?? 4;
           $cleaning_baru = $cleaning['status'] ?? 4;
           $briefing_baru = $briefing['status'] ?? 4;
           $omset_baru = $omset['status'] ?? 4;
           $cod_baru = $cod['status'] ?? 4;

           $row = array();
           $row['DT_RowIndex'] = $no++;
           $row['tanggal'] = formatTanggal($tanggal);
           $row['absen'] = $absen_baru;
           $row['grooming'] = $grooming_baru;
           $row['cleaning'] = $cleaning_baru;
           $row['briefing'] = $briefing_baru;
           $row['omset'] = $omset_baru;
           $row['cod'] = $cod_baru;

           $data[] = $row;

       }

    //    $data[] = [
    //        'DT_RowIndex' => '',
    //        'tanggal' => '',
    //        'stock_pangkalan' => '',
    //        'transaksi_pelanggan' => 'Total Stock Tabung',
    //        'sisa_stock' => $total_stock,
    //    ];
       return $data;
    }

    public function dataReport($awal,$akhir,$id)
    {
        
       $data = $this->getData($awal,$akhir,$id);

       return datatables()
       ->of($data)
       ->addColumn('absen', function($data){
        if($data['absen'] == 0){
            return '<span class="badge badge-danger">Ditolak</span>';

        }else if($data['absen'] == 1){
          return '<span class="badge badge-success">Diterima</span>';
        }else if($data['absen'] == 2){
            return '<span class="badge badge-light">Pending</span>';

        }else{
            return '<span class="badge badge-dark">kosong</span>';
        }
        })
        ->addColumn('grooming', function($data){
            if($data['grooming'] == 0){
                return '<span class="badge badge-danger">Ditolak</span>';
    
            }else if($data['grooming'] == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else if($data['grooming'] == 2){
                return '<span class="badge badge-light">Pending</span>';
    
            }else{
                return '<span class="badge badge-dark">kosong</span>';
            }
            })
        ->addColumn('cleaning', function($data){
            if($data['cleaning'] == 0){
                return '<span class="badge badge-danger">Ditolak</span>';
    
            }else if($data['cleaning'] == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else if($data['cleaning'] == 2){
                return '<span class="badge badge-light">Pending</span>';
    
            }else{
                return '<span class="badge badge-dark">kosong</span>';
            }
            })
        ->addColumn('briefing', function($data){
            if($data['briefing'] == 0){
                return '<span class="badge badge-danger">Ditolak</span>';
    
            }else if($data['briefing'] == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else if($data['briefing'] == 2){
                return '<span class="badge badge-light">Pending</span>';
    
            }else{
                return '<span class="badge badge-dark">kosong</span>';
            }
            })
        ->addColumn('omset', function($data){
            if($data['omset'] == 0){
                return '<span class="badge badge-danger">Ditolak</span>';
    
            }else if($data['omset'] == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else if($data['omset'] == 2){
                return '<span class="badge badge-light">Pending</span>';
    
            }else{
                return '<span class="badge badge-dark">kosong</span>';
            }
            })
        ->addColumn('cod', function($data){
            if($data['cod'] == 0){
                return '<span class="badge badge-danger">Ditolak</span>';
    
            }else if($data['cod'] == 1){
              return '<span class="badge badge-success">Diterima</span>';
            }else if($data['cod'] == 2){
                return '<span class="badge badge-light">Pending</span>';
    
            }else{
                return '<span class="badge badge-dark">kosong</span>';
            }
            })
       ->rawColumns(['absen','grooming','cleaning','briefing','omset','cod'])//biar kebaca html
       ->make(true);
           
    }

    public function exportDaily($awal, $akhir, $id)
    {
        $karyawan = karyawan::findOrFail($id);
        $data =  $this->getData($awal,$akhir,$id);
           $pdf = PDF::loadView('backend.karyawan.laporan.pdf', compact('awal','akhir','data','karyawan'));
           $pdf->setPaper('a4','potrait');
           return $pdf->stream('Laporan Daily Activity'.date('Y-m-d-his').' .pdf');
  
    }
}
