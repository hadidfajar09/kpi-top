<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class OmsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $sales = karyawan::where('jabatan_id', 4)->pluck('name','id');
        return view('backend.omset.index');
    }

    public function data()
    {
        $omset = Omset::
            latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($omset)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('sales', function($omset){
                    return '<span class="badge badge-success">'.$omset->user->name.'</span>';
                })
                // ->addColumn('user', function($omset){
                //     return '<span class="badge badge-primary">'.$omset->user->name.'</span>';
                // })
                ->addColumn('nominal', function($omset){
                    return 'Rp ' . formatUang($omset->nominal);
                })
                ->addColumn('tanggal_setor', function($omset){
                    return formatTanggal($omset->tanggal_setor);
                })
             
                ->addColumn('aksi', function($omset){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('omset.update', $omset->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('omset.destroy', $omset->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','sales','nominal'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($omset)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($pelanggan){
                    return '<input type="checkbox" name="id_pelanggan[]" value="'.$pelanggan->id.'">';
                })
                ->addColumn('kode_pelanggan', function($pelanggan){
                    return '<span class="badge badge-success">'.$pelanggan->kode_pelanggan.'</span>';
                })
                ->addColumn('nominal_gaji', function($pelanggan){
                    return 'Rp ' . formatUang($pelanggan->penghasilan->nominal_gaji);
                })
                ->addColumn('aksi', function($pelanggan){ //untuk aksi
                    $button = '-';
                   return $button;
                })
                ->rawColumns(['aksi','kode_pelanggan','select_all'])//biar kebaca html
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
        $request->validate([
            'tanggal_setor' => 'required',
            'catatan' => 'required',
            'nominal' => 'required',
        ]);

        $omset = new Omset();

        $omset->tanggal_setor = $request->tanggal_setor;
        $omset->karyawan_id = auth()->user()->id;
        $omset->catatan = $request->catatan;
        $omset->nominal = $request->nominal;
        // $omset->user_id = auth()->user()->id;

        $omset->save();

        return redirect()->route('omset.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Omset  $omset
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $omset = Omset::find($id);
        return response()->json($omset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Omset  $omset
     * @return \Illuminate\Http\Response
     */
    public function edit(Omset $omset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Omset  $omset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Omset $omset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Omset  $omset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $omset = Omset::find($id);

        $omset->delete();


    }
}
