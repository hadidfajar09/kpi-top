<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.cleaning.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $cleaning = Cleaning::
            latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($cleaning)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($cleaning){
                    return '<img src="'.$cleaning->path_foto.' " width="150">';
                })
                
                ->addColumn('penempatan', function($cleaning){
                    return '<h1 class="badge badge-dark">'.$cleaning->penempatan->nama.'</h1>';
                })
                ->addColumn('user', function($cleaning){
                    return '<h1 class="badge badge-success">'.$cleaning->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($cleaning){
                    return formatTanggal($cleaning->created_at);
                })
             
                ->addColumn('aksi', function($cleaning){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('cleaning.edit', $cleaning->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('cleaning.destroy', $cleaning->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','penempatan','user','tanggal'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($cleaning)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($karyawan){
                    return '<input type="checkbox" name="id_pelanggan[]" value="'.$karyawan->id.'">';
                })
                ->addColumn('kode_pelanggan', function($karyawan){
                    return '<span class="badge badge-success">'.$karyawan->kode_pelanggan.'</span>';
                })
                ->addColumn('nominal_gaji', function($karyawan){
                    return 'Rp ' . formatUang($karyawan->penghasilan->nominal_gaji);
                })
                ->addColumn('aksi', function($karyawan){ //untuk aksi
                    $button = '-';
                   return $button;
                })
                ->rawColumns(['aksi','kode_pelanggan','select_all'])//biar kebaca html
                ->make(true);
            }
        
    }

    public function create()
    {
        $penempatan = Penempatan::all()->pluck('nama','id');

        return view('backend.cleaning.create', compact('penempatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cleaning = new Cleaning();

        $cleaning->penempatan_id = $request->penempatan_id;
        $cleaning->catatan = $request->catatan;
        $cleaning->created_at = now();
        $cleaning->user_id = auth()->user()->id;

            $img = $request->path_foto;
            $folderPath = "foto_clean/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto = 'uploads/foto_clean/'.$fileName;

        $cleaning->save();

        return redirect()->route('cleaning.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cleaning  $cleaning
     * @return \Illuminate\Http\Response
     */
    public function show(Cleaning $cleaning)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cleaning  $cleaning
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cleaning = Cleaning::findOrfail($id);
        $penempatan = Penempatan::all();

        return view('backend.cleaning.edit',compact('penempatan','cleaning'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cleaning  $cleaning
     * @return \Illuminate\Http\Response
     */
    public function updated(Request $request, $id)
    {
        $cleaning = Cleaning::find($id);

        $cleaning->penempatan_id = $request->penempatan_id;
        $cleaning->catatan = $request->catatan;
        $cleaning->created_at = now();
        $cleaning->user_id = auth()->user()->id;

        if ($request->path_foto) {
            unlink($cleaning->path_foto);
            $img = $request->path_foto;
            $folderPath = "foto_clean/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto = 'uploads/foto_clean/'.$fileName;
            
        }

        $cleaning->update();

        return redirect()->route('cleaning.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cleaning  $cleaning
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cleaning = Cleaning::find($id);
        unlink($cleaning->path_foto);

        $cleaning->delete();

        return response()->json('data berhasil dihapus');
    }
}
