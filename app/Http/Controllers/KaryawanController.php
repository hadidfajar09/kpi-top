<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\kontrak;
use App\Models\karyawan;
use App\Models\Shift;
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
             
                
                ->addColumn('end_work', function($karyawan){
                    return formatTanggal($karyawan->end_work);
                })

                ->addColumn('status', function($karyawan){
                    if($karyawan->end_work <= now()){
                        return '<span class="badge badge-danger">NonAKtif</span>';
      
                    }else{
                      return '<span class="badge badge-success">Aktif</span>';
                    }
                })
             
                ->addColumn('aksi', function($karyawan){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('karyawan.edit', $karyawan->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('karyawan.destroy', $karyawan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> <a href="'.route('file.download', $karyawan->id).'" class="btn btn-xs btn-warning btn-flat" target="_blank"><i class="fa fa-download"></i></a> <a href="'.route('karyawan.detail', $karyawan->id).'" class="btn btn-xs btn-success btn-flat" target="_blank"><i class="fas fa-chart-pie"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','end_work','status'])//biar kebaca html
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

    public function detail($id)
    {
        $karyawan_detail = karyawan::findOrFail($id);
        
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
            'jabatan_id' => 'required',
            'kontrak_id' => 'required',
            'penempatan_id' => 'required',
            'shift_id' => 'required',
            'berkas' => 'required',
            'alamat' => 'required',
            'nomor' => 'required',
            'join_date' => 'required',
            'end_work' => 'required',
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
        }

        $karyawan->save();

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
        //
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
            'berkas' => 'required',
            'alamat' => 'required',
            'nomor' => 'required',
            'join_date' => 'required',
            'end_work' => 'required',
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
            unlink($old);
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
        unlink($karyawan->foto);
        File::delete($karyawan->path_berkas);

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
}
