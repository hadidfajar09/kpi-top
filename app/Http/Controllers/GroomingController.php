<?php

namespace App\Http\Controllers;

use App\Models\grooming;
use App\Models\karyawan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GroomingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('backend.grooming.index');
    }

    public function data()
    {
        $grooming = grooming::
            latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($grooming)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($grooming){
                    return '<img src="'.$grooming->path_foto.' " width="150">';
                })
                
                ->addColumn('karyawan', function($grooming){
                    return '<h1 class="badge badge-dark">'.$grooming->karyawan->name.'</h1>';
                })
                ->addColumn('user', function($grooming){
                    return '<h1 class="badge badge-success">'.$grooming->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($grooming){
                    return formatTanggal($grooming->created_at);
                })

                ->addColumn('status', function($grooming){
                    if($grooming->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($grooming->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
             
                ->addColumn('aksi', function($grooming){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('grooming.edit', $grooming->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('grooming.destroy', $grooming->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('grooming.acc', $grooming->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('grooming.decline', $grooming->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','karyawan','user','tanggal','status'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($grooming)//source
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $karyawan = karyawan::all()->pluck('name','id');

        return view('backend.grooming.create', compact('karyawan'));
    }

    public function accept($id)
    {
        grooming::findOrFail($id)->update([
            'status' => 1
        ]);

      
       return redirect()->back();

    }

    public function decline($id)
    {
        grooming::findOrFail($id)->update([
            'status' => 0
        ]);

      
       return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $grooming = new grooming();

        $grooming->karyawan_id = $request->karyawan_id;
        $grooming->catatan = $request->catatan;
        $grooming->created_at = now();
        $grooming->user_id = auth()->user()->id;

            $img = $request->path_foto;
            $folderPath = "foto/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $grooming->path_foto = 'uploads/foto/'.$fileName;

        $grooming->save();

        return redirect()->route('grooming.index');


        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function show(grooming $grooming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grooming = grooming::findOrfail($id);
        $karyawan = karyawan::all();

        return view('backend.grooming.edit',compact('karyawan','grooming'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function updated(Request $request, $id)
    {
        $grooming = grooming::find($id);

        $grooming->karyawan_id = $request->karyawan_id;
        $grooming->catatan = $request->catatan;
        $grooming->created_at = now();
        $grooming->user_id = auth()->user()->id;

        if ($request->path_foto) {
            unlink($grooming->path_foto);
            $img = $request->path_foto;
            $folderPath = "foto/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $grooming->path_foto = 'uploads/foto/'.$fileName;
            
        }

        $grooming->update();

        return redirect()->route('grooming.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grooming = grooming::find($id);
        unlink($grooming->path_foto);

        $grooming->delete();

        return response()->json('data berhasil dihapus');
    }
}
