<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Distribusi;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agent = User::where('level',1)->pluck('name','id');
        $pangkalan = User::where('level',2)->pluck('name','id');
        return view('backend.distribusi.index',compact('agent','pangkalan'));
    }

    public function data()
    {
        $distribusi = Distribusi::orderBy('id','desc')->get();

        if (auth()->user()->level == 0 || auth()->user()->level == 3) {
            return datatables()
            ->of($distribusi)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('tanggal_pengantaran', function($distribusi){
                return formatTanggal($distribusi->tanggal_pengantaran);
            })
            ->addColumn('nama_agent', function($distribusi){
                $nama_agent = User::where('id', $distribusi->id_agent)->first();
                return $nama_agent->name;
            })
            ->addColumn('nama_pangkalan', function($distribusi){
                $nama_pangkalan = User::where('id', $distribusi->id_pangkalan)->first();
                return $nama_pangkalan->name;
            })
            ->addColumn('kecamatan', function($distribusi){

                $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();
                
                $nama_kecamatan = Kecamatan::where('id', $pangkalan->id_kecamatan)->first();
                return $nama_kecamatan->nama_kecamatan;
            })
            ->addColumn('desa', function($distribusi){

                $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();
                
                $nama_desa = Desa::where('id', $pangkalan->id_desa)->first();
                return $nama_desa->nama_desa;
            })
            ->addColumn('status', function($distribusi){ 
              if($distribusi->status == 0){
                  return '<span class="badge badge-danger">Belum</span>';

              }else{
                return '<span class="badge badge-success">Selesai</span>';
              }
            
            })
            ->addColumn('aksi', function($distribusi){ //untuk aksi
                $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('distribusi.update', $distribusi->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('distribusi.destroy', $distribusi->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
               return $button;
            })
            ->rawColumns(['aksi','status'])//biar kebaca html
            ->make(true);
        }else{
            return datatables()
            ->of($distribusi)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('tanggal_pengantaran', function($distribusi){
                return formatTanggal($distribusi->tanggal_pengantaran);
            })
            ->addColumn('nama_agent', function($distribusi){
                $nama_agent = User::where('id', $distribusi->id_agent)->first();
                return $nama_agent->name;
            })
            ->addColumn('nama_pangkalan', function($distribusi){
                $nama_pangkalan = User::where('id', $distribusi->id_pangkalan)->first();
                return $nama_pangkalan->name;
            })
            ->addColumn('kecamatan', function($distribusi){

                $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();
                
                $nama_kecamatan = Kecamatan::where('id', $pangkalan->id_kecamatan)->first();
                return $nama_kecamatan->nama_kecamatan;
            })
            ->addColumn('desa', function($distribusi){

                $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();
                
                $nama_desa = Desa::where('id', $pangkalan->id_desa)->first();
                return $nama_desa->nama_desa;
            })
            ->addColumn('status', function($distribusi){ 
              if($distribusi->status == 0){
                  return '<span class="badge badge-danger">Belum</span>';

              }else{
                return '<span class="badge badge-success">Selesai</span>';
              }
            
            })
            ->addColumn('aksi', function($distribusi){ //untuk aksi
                $button = '-';
               return $button;
            })
            ->rawColumns(['aksi','status'])//biar kebaca html
            ->make(true);
        }
       
    }

    public function GetPangkalan($agent_id)
    {
        $desa = User::where('id_agent', $agent_id)->get();

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
       
        $distribusi = new Distribusi();
        $distribusi->tanggal_pengantaran = $request->tanggal_pengantaran;
        $distribusi->drop_tabung = $request->drop_tabung;
  
        $distribusi->id_agent = $request->id_agent;
        $distribusi->id_pangkalan = $request->id_pangkalan;

        $distribusi->status = 0;

        $distribusi->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $distribusi = Distribusi::find($id);
        return response()->json($distribusi);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function edit(Distribusi $distribusi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         
        $distribusi = Distribusi::find($id);
        $distribusi->tanggal_pengantaran = $request->tanggal_pengantaran;
        $distribusi->drop_tabung = $request->drop_tabung;
  
        $distribusi->id_agent = $request->id_agent;
        if($request->id_pangkalan){
            $distribusi->id_pangkalan = $request->id_pangkalan;
        }
       
        $distribusi->status = 0;

        $distribusi->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Distribusi  $distribusi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $distribusi = Distribusi::find($id);

      if($distribusi->status == 1){
          $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();

          $pangkalan->stock_tabung -= $distribusi->drop_tabung;
          $pangkalan->update();
      }

        $distribusi->delete();

        return response()->json('data berhasil dihapus');
    }
}
