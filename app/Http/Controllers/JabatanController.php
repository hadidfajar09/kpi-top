<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.jabatan.index');
    }

    public function data()
    {
        $jabatan = Jabatan::orderBy('jabatan','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($jabatan)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('jabatan', function($jabatan){
            return '<h1 class="badge badge-info">'.$jabatan->jabatan.'</h1>';
        })
        ->addColumn('aksi', function($jabatan){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('jabatan.update', $jabatan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('jabatan.destroy', $jabatan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
           return $button;
        })
        ->rawColumns(['aksi','jabatan'])//biar kebaca html
        ->make(true);

    }else{
    return datatables()
    ->of($jabatan)//source
    ->addIndexColumn() //untuk nomer
    ->addColumn('jabatan', function($jabatan){
        return '<h1 class="badge badge-info">'.$jabatan->jabatan.'</h1>';
    })
    ->addColumn('aksi', function($jabatan){ //untuk aksi
        $button = '-';
       return $button;
    })
    ->rawColumns(['aksi','jabatan'])//biar kebaca html
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
        $jabatan = Jabatan::latest()->first() ?? new Jabatan();
        $jabatan = new Jabatan();
        $jabatan->jabatan = $request->jabatan;
        $jabatan->deskripsi = $request->deskripsi;
        $jabatan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jabatan = Jabatan::find($id);
        return response()->json($jabatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
