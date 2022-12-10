<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kecamatan = Kecamatan::all()->pluck('nama_kecamatan','id');
        return view('backend.desa.index', compact('kecamatan'));
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

    public function data()
    {
        $desa = Desa::leftJoin('kecamatans', 'kecamatans.id', 'desas.id_kecamatan')
            ->select('desas.*','nama_kecamatan')
            ->orderBy('nama_desa','asc')->get();

        return datatables()
            ->of($desa)//source
            ->addIndexColumn() //untuk nomer
           ->addColumn('aksi', function($desa){ //untuk aksi
                $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('desa.update', $desa->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('desa.destroy', $desa->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
               return $button;
            })
            ->rawColumns(['aksi'])//biar kebaca html
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

        $desa = Desa::create($request->all());

        return response()->json('Desa Berhasil Disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $desa = Desa::find($id);
        return response()->json($desa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function edit(Desa $desa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $desa = Desa::find($id);
        $desa->update($request->all());

        return response()->json('Desa Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $desa = Desa::find($id);
        $desa->delete();

        return response()->json('data berhasil dihapus');
    }
}
