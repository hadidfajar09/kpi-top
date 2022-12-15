<?php

namespace App\Http\Controllers;

use App\Models\Penempatan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.penempatan.index');
    }

    public function data()
    {
        $penempatan = Penempatan::orderBy('nama','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($penempatan)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('nama', function($penempatan){
            return '<h1 class="badge badge-light">'.$penempatan->nama.'</h1>';
        })
        ->addColumn('aksi', function($penempatan){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('penempatan.update', $penempatan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('penempatan.destroy', $penempatan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
           return $button;
        })
        ->rawColumns(['aksi','nama'])//biar kebaca html
        ->make(true);

    }else{
    return datatables()
    ->of($penempatan)//source
    ->addIndexColumn() //untuk nomer
    ->addColumn('nama', function($penempatan){
        return '<h1 class="badge badge-light">'.$penempatan->nama.'</h1>';
    })
    ->addColumn('aksi', function($penempatan){ //untuk aksi
        $button = '-';
       return $button;
    })
    ->rawColumns(['aksi','nama'])//biar kebaca html
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
        $pemempatan = Penempatan::latest()->first() ?? new Penempatan();
        $pemempatan = new Penempatan();
        $pemempatan->nama = $request->nama;
        $pemempatan->alamat = $request->alamat;
        $pemempatan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penempatan = Penempatan::find($id);
        return response()->json($penempatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penempatan $penempatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penempatan = Penempatan::find($id);
        $penempatan->nama = $request->nama;
        $penempatan->alamat = $request->alamat;

        $penempatan->update();

        return response()->json('Penempatan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penempatan = Penempatan::find($id);

        // $pangkalan = User::where('id_agent', $agent->id)->get();
        // $distribusi = Distribusi::where('id_agent',$id)->get();

        
        // foreach ($pangkalan as $row) {
        //     $row->delete();
        // }

        // foreach ($distribusi as $row) {
        //     $row->delete();
        // }


        $penempatan->delete();

        return response()->json('data berhasil dihapus');
    }
}
