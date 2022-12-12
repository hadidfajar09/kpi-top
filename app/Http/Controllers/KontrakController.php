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
            return '<h1 class="badge badge-info">'.$kontrak->kontrak.'</h1>';
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
        return '<h1 class="badge badge-info">'.$kontrak->kontrak.'</h1>';
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function show(kontrak $kontrak)
    {
        //
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
    public function update(Request $request, kontrak $kontrak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kontrak  $kontrak
     * @return \Illuminate\Http\Response
     */
    public function destroy(kontrak $kontrak)
    {
        //
    }
}
