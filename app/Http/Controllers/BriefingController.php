<?php

namespace App\Http\Controllers;

use App\Models\Briefing;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BriefingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.briefing.index');
    }

    public function data()
    {
        $briefing = Briefing::
            latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($briefing)//source
                ->addIndexColumn() //untuk nomer
                // ->addColumn('path_foto', function($briefing){
                //     return '<img src="'.$briefing->path_foto.' " width="150">';
                // })
                ->addColumn('path_foto', function($briefing){
                    return ' <a href="'.$briefing->path_foto.'" data-toggle="lightbox">
                    <img src="'.$briefing->path_foto.'" class="img-fluid" alt="">
                  </a>';
                })
                
                ->addColumn('penempatan', function($briefing){
                    return '<h1 class="badge badge-dark">'.$briefing->penempatan->nama.'</h1>';
                })
                ->addColumn('user', function($briefing){
                    return '<h1 class="badge badge-success">'.$briefing->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($briefing){
                    $result = Carbon::parse($briefing->created_at);
                    return $result;
                })

                ->addColumn('status', function($briefing){
                    if($briefing->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($briefing->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
             
                ->addColumn('aksi', function($briefing){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('briefing.edit', $briefing->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('briefing.destroy', $briefing->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('briefing.acc', $briefing->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('briefing.decline', $briefing->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','penempatan','user','tanggal','status'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($briefing)//source
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
        $penempatan = Penempatan::all()->pluck('nama','id');

        return view('backend.briefing.create', compact('penempatan'));
    }

    public function accept($id)
    {
        Briefing::findOrFail($id)->update([
            'status' => 1
        ]);

      
       return redirect()->back();

    }

    public function decline($id)
    {
        Briefing::findOrFail($id)->update([
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
        $briefing = new Briefing();

        $briefing->penempatan_id = $request->penempatan_id;
        $briefing->catatan = $request->catatan;
        $briefing->created_at = now();
        $briefing->user_id = auth()->user()->id;

            $img = $request->path_foto;
            $folderPath = "foto_briefing/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $briefing->path_foto = 'uploads/foto_briefing/'.$fileName;

        $briefing->save();

        return redirect()->route('briefing.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Briefing  $briefing
     * @return \Illuminate\Http\Response
     */
    public function show(Briefing $briefing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Briefing  $briefing
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $briefing = Briefing::findOrfail($id);
        $penempatan = Penempatan::all();

        return view('backend.briefing.edit',compact('penempatan','briefing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Briefing  $briefing
     * @return \Illuminate\Http\Response
     */
    public function updated(Request $request, $id)
    {
        $briefing = Briefing::find($id);

        $briefing->penempatan_id = $request->penempatan_id;
        $briefing->catatan = $request->catatan;
        $briefing->created_at = now();
        $briefing->user_id = auth()->user()->id;

        if ($request->path_foto) {
            unlink($briefing->path_foto);
            $img = $request->path_foto;
            $folderPath = "foto_briefing/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $briefing->path_foto = 'uploads/foto_briefing/'.$fileName;
            
        }

        $briefing->update();

        return redirect()->route('briefing.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Briefing  $briefing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $briefing = Briefing::find($id);
        unlink($briefing->path_foto);

        $briefing->delete();

        return response()->json('data berhasil dihapus');
    }
}
