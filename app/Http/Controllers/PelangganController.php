<?php

namespace App\Http\Controllers;

use App\Imports\PelangganImport;
use App\Models\Pelanggan;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Pekerjaan;
use App\Models\Penghasilan;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kecamatan = Kecamatan::all()->pluck('nama_kecamatan','id');
        $desa = Desa::all()->pluck('nama_desa','id');
        $penghasilan = Penghasilan::orderBy('nominal_gaji','asc')->pluck('nominal_gaji','id');
        $pekerjaan = Pekerjaan::all()->pluck('nama_pekerjaan','id');
        $pangkalan = User::where('level',2)->pluck('name','id');
        return view('backend.pelanggan.index', compact('kecamatan','desa','penghasilan','pekerjaan','pangkalan'));
    }

    public function data()
    {
        $pelanggan = Pelanggan::leftJoin('kecamatans', 'kecamatans.id', 'pelanggans.id_kecamatan')
            ->leftJoin('desas', 'desas.id', 'pelanggans.id_desa')
            ->leftJoin('pekerjaans', 'pekerjaans.id', 'pelanggans.id_pekerjaan')
            ->leftJoin('penghasilans', 'penghasilans.id', 'pelanggans.id_penghasilan')
            ->leftJoin('users', 'users.id', 'pelanggans.id_pangkalan')
            ->select('pelanggans.*','nama_kecamatan','nama_desa','nama_pekerjaan','name')
            ->orderBy('nama_pelanggan','asc')->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($pelanggan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($pelanggan){
                    return '<input type="checkbox" name="id_pelanggan[]" value="'.$pelanggan->id.'">';
                })
                ->addColumn('kode_pelanggan', function($pelanggan){
                    return '<span class="badge badge-success">'.$pelanggan->kode_pelanggan.'</span>';
                })
                ->addColumn('nominal_gaji', function($pelanggan){
                    return 'Rp ' . formatUang($pelanggan->penghasilan->nominal_gaji);
                })
             
                ->addColumn('aksi', function($pelanggan){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('pelanggan.update', $pelanggan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('pelanggan.destroy', $pelanggan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','kode_pelanggan','select_all'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($pelanggan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($pelanggan){
                    return '<input type="checkbox" name="id_pelanggan[]" value="'.$pelanggan->id.'">';
                })
                ->addColumn('kode_pelanggan', function($pelanggan){
                    return '<span class="badge badge-success">'.$pelanggan->kode_pelanggan.'</span>';
                })
                ->addColumn('nominal_gaji', function($pelanggan){
                    return 'Rp ' . formatUang($pelanggan->penghasilan->nominal_gaji);
                })
                ->addColumn('aksi', function($pelanggan){ //untuk aksi
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
        $pelanggan = Pelanggan::latest()->first() ?? new Pelanggan();
        $request['kode_pelanggan'] = 'W'. tambahNolDepan((int)$pelanggan->id+1, 6);
        $request['nama_pelanggan'] = strtoupper($request->nama_pelanggan);
        $request['qrcode'] = $request->nik.'-'.$request->nama_pelanggan;


        $pelanggan = Pelanggan::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);
        return response()->json($pelanggan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);
        $request['nama_pelanggan'] = strtoupper($request->nama_pelanggan);
        if($request->id_desa){
            $pelanggan->id_desa = $request->id_desa;
        }
        $request['qrcode'] = $request->nik.'-'.$request->nama_pelanggan;
        $pelanggan->update($request->all());

        return response()->json('Pelanggan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        $transaksi = Transaksi::where('id_pelanggan', $id)->get();
        
        foreach ($transaksi as $row) {
            $row->delete();
        }


        $pelanggan->delete();

        return response()->json('data berhasil dihapus');
    }


    public function cetakQrcode(Request $request)
    {

        $datapelanggan =  collect(array());

        foreach($request->id_pelanggan as $id ){
            $pelanggan = Pelanggan::find($id);
            $datapelanggan[] = $pelanggan;
        }
        $datapelanggan = $datapelanggan->chunk(2); //mecah array

        $setting = Setting::first();

        $pdf = PDF::loadView('backend.pelanggan.qrcode', compact('datapelanggan','setting'));

        $pdf->setPaper(array(0,0,566.93,750.39),'potrait');

        return $pdf->stream('kartu_pelanggan.pdf');

    }

    public function cetakJPG(Request $request)
    {
        $datapelanggan =  collect(array());
        $id = $request->id_pelanggan;
        $pelanggan = Pelanggan::find($id);
        $datapelanggan[] = $pelanggan;

        $setting = Setting::first();

        return view('backend.pelanggan.jpg', compact('pelanggan', 'setting'));
    }

    public function importExcel(Request $request)
    {
        $data = $request->file('file_excel');

        $namaFile = $data->getClientOriginalName();

        $data->move('importPelanggan',$namaFile);
 
        Excel::import(new PelangganImport, public_path('/importPelanggan/'.$namaFile));

        return response()->json('Data Berhasil Di-import');
    }

    public function resetBulanan(Request $request)
    {
        
        $pelanggan = Pelanggan::orderBy('id','desc')->get();
        foreach ($pelanggan as $row) {
            $row->jumlah_tabung = $row->jatah_bulanan;
            
            $row->update();
        }


        return response()->json('Jatah Tabung Berhasil direset');
    }
}
