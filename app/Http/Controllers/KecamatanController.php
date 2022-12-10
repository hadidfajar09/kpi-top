<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('backend.kecamatan.index');
    }

    public function data()
    {
        $kecamatan = Kecamatan::orderBy('id','desc')->get();

        return datatables()
            ->of($kecamatan)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('harga_tabung', function($kecamatan){
                return 'Rp ' . formatUang($kecamatan->harga_tabung);
            })
            ->addColumn('aksi', function($kecamatan){ //untuk aksi
                $button = '<div class="btn-group"> <button onclick="editForm(`'.route('kecamatan.update', $kecamatan->id).'`)" class="btn bg-gradient-info btn-xs"><i class="fas fa-edit"></i></button><button onclick="deleteData(`'.route('kecamatan.destroy', $kecamatan->id).'`)" class="btn bg-gradient-danger btn-xs delete-kecamatan" ><i class="fas fa-trash"></i></button> </div>';

               return $button;
               
            })
            ->rawColumns(['aksi'])//biar kebaca
            ->make(true);
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
        $kecamatan = new Kecamatan();
        $kecamatan->nama_kecamatan = $request->nama_kecamatan;
        $kecamatan->harga_tabung = $request->harga_tabung;
        $kecamatan->save();

        return response()->json('Data Kecamatan Berhasil Disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kecamatan = Kecamatan::find($id);
        return response()->json($kecamatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kecamatan = Kecamatan::find($id);
        $kecamatan->nama_kecamatan = $request->nama_kecamatan;
        $kecamatan->harga_tabung = $request->harga_tabung;
        $kecamatan->update();

        return response()->json('Data Pekerjaan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kecamatan = Kecamatan::find($id);
        $desa = Desa::where('id_kecamatan', $kecamatan->id)->get();

        
        foreach ($desa as $row) {
            $row->delete();
        }

        $kecamatan->delete();

        return response()->json('data berhasil dihapus');
    }
}
