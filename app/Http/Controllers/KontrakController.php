<?php

namespace App\Http\Controllers;

use App\Models\kontrak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.kontrak.index');
    }


    public function data()
    {
        $kontrak = kontrak::orderBy('kontrak','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($kontrak)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('kontrak', function($kontrak){
            return '<h1 class="badge badge-success">'.$kontrak->kontrak.'</h1>';
        })
        ->addColumn('aksi', function($kontrak){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('kontrak.update', $kontrak->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('kontrak.destroy', $kontrak->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
           return $button;
        })
        ->rawColumns(['aksi','kontrak'])//biar kebaca html
        ->make(true);

    }else{
    return datatables()
    ->of($kontrak)//source
    ->addIndexColumn() //untuk nomer
    ->addColumn('kontrak', function($kontrak){
        return '<h1 class="badge badge-success">'.$kontrak->kontrak.'</h1>';
    })
    ->addColumn('aksi', function($kontrak){ //untuk aksi
        $button = '-';
       return $button;
    })
    ->rawColumns(['aksi','kontrak'])//biar kebaca html
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
        $kontrak = kontrak::latest()->first() ?? new kontrak();
        $kontrak = new kontrak();
        $kontrak->kontrak = $request->kontrak;
        $kontrak->deskripsi = $request->deskripsi;
        $kontrak->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kontrak = kontrak::find($id);
        return response()->json($kontrak);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function edit(kontrak $kontrak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::find($id);
        $kontrak->kontrak = $request->kontrak;
        $kontrak->deskripsi = $request->deskripsi;

        $kontrak->update();

        return response()->json('Masa Kontrak Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kontrak = kontrak::find($id);

        // $pangkalan = User::where('id_agent', $agent->id)->get();
        // $distribusi = Distribusi::where('id_agent',$id)->get();

        
        // foreach ($pangkalan as $row) {
        //     $row->delete();
        // }

        // foreach ($distribusi as $row) {
        //     $row->delete();
        // }


        $kontrak->delete();

        return response()->json('data berhasil dihapus');
    }
}
