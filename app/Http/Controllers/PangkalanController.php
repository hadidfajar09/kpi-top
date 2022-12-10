<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Desa;
use App\Models\Distribusi;
use App\Models\Kecamatan;
use App\Models\Pangkalan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class PangkalanController extends Controller
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
        $agent = User::where('level',1)->pluck('name','id');
        return view('backend.pangkalan.index', compact('kecamatan','desa','agent'));
    }

    public function data()
    {
        $pangkalan = User::where('level', 2)
            ->leftJoin('kecamatans', 'kecamatans.id', 'users.id_kecamatan')
            ->leftJoin('desas', 'desas.id', 'users.id_desa')
     
            ->select('users.*','nama_kecamatan','harga_tabung','nama_desa')
            ->orderBy('name','asc')->get();

            if(auth()->user()->level == 0 || auth()->user()->level == 3){
            
        return datatables()
            ->of($pangkalan)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('kode_user', function($pangkalan){
                return '<span class="badge badge-info">'.$pangkalan->kode_user.'</span>';
            })
            ->addColumn('nama_agent', function($pangkalan){
                $nama_agent = User::where('id', $pangkalan->id_agent)->first();
                return $nama_agent->name;
            })
            ->addColumn('harga_tabung', function($pangkalan){
                return 'Rp ' . formatUang($pangkalan->harga_tabung);
            })
            ->addColumn('aksi', function($pangkalan){ //untuk aksi
                $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('pangkalan.update', $pangkalan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('pangkalan.destroy', $pangkalan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
               return $button;
            })
            ->rawColumns(['aksi','kode_user'])//biar kebaca html
            ->make(true);

        }else{
            return datatables()
            ->of($pangkalan)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('kode_user', function($pangkalan){
                return '<span class="badge badge-info">'.$pangkalan->kode_user.'</span>';
            })
            ->addColumn('nama_agent', function($pangkalan){
                $nama_agent = User::where('id', $pangkalan->id_agent)->first();
                return $nama_agent->name;
            })
            ->addColumn('harga_tabung', function($pangkalan){
                return 'Rp ' . formatUang($pangkalan->harga_tabung);
            })
            ->addColumn('aksi', function($pangkalan){ //untuk aksi
                $button = '-';
               return $button;
            })
            ->rawColumns(['aksi','kode_user'])//biar kebaca html
            ->make(true);
        }
    }

    public function GetDesa($kecamatan_id)
    {
        $desa = Desa::where('id_kecamatan', $kecamatan_id)->get();

        return response()->json($desa);
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
             
        $pangkalan = User::latest()->first() ?? new User();
        $request->kode_user = 'P'. tambahNolDepan((int)$pangkalan->id+1, 4);

        $pangkalan = new User();
        $pangkalan->name = $request->name;
        $pangkalan->email = $request->email;
        $pangkalan->alamat = $request->alamat;
        $pangkalan->kode_user = $request->kode_user;
        $pangkalan->password = bcrypt($request->password);
        $pangkalan->stock_tabung = $request->stock_tabung;
        $pangkalan->id_agent = $request->id_agent;
        $pangkalan->id_kecamatan = $request->id_kecamatan;
        $pangkalan->id_desa = $request->id_desa;
        $pangkalan->level = 2;

        $pangkalan->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pangkalan  $pangkalan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pangkalan = User::find($id);
        return response()->json($pangkalan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pangkalan  $pangkalan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pangkalan $pangkalan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pangkalan  $pangkalan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pangkalan = User::find($id);
        $pangkalan->name = $request->name;
        $pangkalan->email = $request->email;
        $pangkalan->alamat = $request->alamat;
        $pangkalan->stock_tabung = $request->stock_tabung;
        $pangkalan->id_kecamatan = $request->id_kecamatan;
        if($request->id_desa){
            $pangkalan->id_desa = $request->id_desa;
        }
        $pangkalan->id_agent = $request->id_agent;

        if($request->has('password') && $request->password != ""){
            $pangkalan->password = bcrypt($request->password);
        }
        $pangkalan->update();

        return response()->json('Pangkalan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pangkalan  $pangkalan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pangkalan = User::find($id);

        $transaksi = Transaksi::where('id_pangkalan', $id)->get();
        
        foreach ($transaksi as $row) {
            $row->delete();
        }

        $distribusi = Distribusi::where('id_pangkalan', $id)->get();
        
        foreach ($distribusi as $row) {
            $row->delete();
        }

        $pangkalan->delete();

        return response()->json('data berhasil dihapus');
    }
}
