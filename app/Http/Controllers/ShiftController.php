<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\karyawan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.shift.index');
    }

    public function data()
    {
        $shift = Shift::orderBy('nama_shift','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($shift)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('nama_shift', function($shift){
            return '<h1 class="badge badge-warning">'.$shift->nama_shift.'</h1>';
        })
        ->addColumn('masuk', function($shift){
            return '<h1 class="badge badge-success">'.$shift->masuk.'</h1>';
        })
        ->addColumn('istirahat', function($shift){
            return '<h1 class="badge badge-info">'.$shift->istirahat.'</h1>';
        })
        ->addColumn('pulang', function($shift){
            return '<h1 class="badge badge-danger">'.$shift->pulang.'</h1>';
        })
        ->addColumn('aksi', function($shift){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('shift.update', $shift->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('shift.destroy', $shift->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
           return $button;
        })
        ->rawColumns(['aksi','nama_shift','masuk','istirahat','pulang'])//biar kebaca html
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
        $shift = new Shift();
        $shift->nama_shift = $request->nama_shift;
        $shift->masuk = $request->masuk;
        $shift->istirahat = $request->istirahat;
        $shift->pulang = $request->pulang;
        $shift->save();

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
        $shift = Shift::find($id);
        return response()->json($shift);
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
        $shift = Shift::find($id);
        $shift->nama_shift = $request->nama_shift;
        $shift->masuk = $request->masuk;
        $shift->istirahat = $request->istirahat;
        $shift->pulang = $request->pulang;

        $shift->update();

        return response()->json('Shift Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shift = Shift::find($id);

        $karyawan = karyawan::where('shift_id', $id)->get();
        // $distribusi = Distribusi::where('id_agent',$id)->get();

        
        foreach ($karyawan as $row) {
            $row->delete();
        }

        $shift->delete();

        return response()->json('data berhasil dihapus');
    }
}
