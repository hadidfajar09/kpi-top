<?php

namespace App\Http\Controllers;

use App\Models\Penghasilan;
use Illuminate\Http\Request;

class PenghasilanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.penghasilan.index');
    }

    public function data()
    {
        $penghasilan = Penghasilan::orderBy('id','desc')->get();

        return datatables()
            ->of($penghasilan)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('nominal_gaji', function($penghasilan){
                return 'Rp ' . formatUang($penghasilan->nominal_gaji);
            })
            ->addColumn('aksi', function($penghasilan){ //untuk aksi
                $button = '<div class="btn-group"> <button onclick="editForm(`'.route('penghasilan.update', $penghasilan->id).'`)" class="btn bg-gradient-info btn-xs"><i class="fas fa-edit"></i></button><button onclick="deleteData(`'.route('penghasilan.destroy', $penghasilan->id).'`)" class="btn bg-gradient-danger btn-xs"><i class="fas fa-trash"></i></button> </div>';

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
        $penghasilan = new Penghasilan();
        $penghasilan->nominal_gaji = $request->nominal_gaji;
        $penghasilan->save();

        return response()->json('Data Pekerjaan Berhasil Disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penghasilan  $penghasilan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penghasilan = Penghasilan::find($id);
        return response()->json($penghasilan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penghasilan  $penghasilan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penghasilan $penghasilan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penghasilan  $penghasilan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penghasilan = Penghasilan::find($id);
        $penghasilan->nominal_gaji = $request->nominal_gaji;
        $penghasilan->update();

        return response()->json('Data Pekerjaan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penghasilan  $penghasilan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penghasilan = Penghasilan::find($id);
        
        $penghasilan->delete();

        return response()->json('data berhasil dihapus');
    }
}
