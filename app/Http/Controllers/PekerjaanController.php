<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.pekerjaan.index');
    }

    public function data()
    {
        $pekerjaan = Pekerjaan::orderBy('id','desc')->get();

        return datatables()
            ->of($pekerjaan)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('aksi', function($pekerjaan){ //untuk aksi
                $button = '<div class="btn-group"> <button onclick="editForm(`'.route('pekerjaan.update', $pekerjaan->id).'`)" class="btn bg-gradient-info btn-xs"><i class="fas fa-edit"></i></button><button onclick="deleteData(`'.route('pekerjaan.destroy', $pekerjaan->id).'`)" class="btn bg-gradient-danger btn-xs"><i class="fas fa-trash"></i></button> </div>';

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
        $pekerjaan = new Pekerjaan();
        $pekerjaan->nama_pekerjaan = $request->nama_pekerjaan;
        $pekerjaan->save();

        return response()->json('Data Pekerjaan Berhasil Disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pekerjaan  $pekerjaan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pekerjaan = Pekerjaan::find($id);
        return response()->json($pekerjaan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pekerjaan  $pekerjaan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pekerjaan $pekerjaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pekerjaan  $pekerjaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pekerjaan = Pekerjaan::find($id);
        $pekerjaan->nama_pekerjaan = $request->nama_pekerjaan;
        $pekerjaan->update();

        return response()->json('Data Pekerjaan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pekerjaan  $pekerjaan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pekerjaan = Pekerjaan::find($id);
        
        $pekerjaan->delete();

        return response()->json('data berhasil dihapus');
    }
}
