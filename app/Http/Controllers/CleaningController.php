<?php

namespace App\Http\Controllers;

use App\Models\Cleaning;
use App\Models\Penempatan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                    return ' <a href="'.$cleaning->path_foto.'" data-toggle="lightbox">
                    <img src="'.$cleaning->path_foto.'" class="img-fluid" alt="" data-(width|height)="[0-9]+">
                  </a>';
                })
                
                
                ->addColumn('penempatan', function($cleaning){
                    return '<h1 class="badge badge-dark">'.$cleaning->penempatan->nama.'</h1>';
                })
                ->addColumn('user', function($cleaning){
                    return '<h1 class="badge badge-success">'.$cleaning->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($cleaning){
                    $result = Carbon::parse($cleaning->created_at);
                    return $result;
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
             
                ->addColumn('aksi', function($cleaning){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('cleaning.edit', $cleaning->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('cleaning.destroy', $cleaning->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('cleaning.acc', $cleaning->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('cleaning.decline', $cleaning->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a><a href="'.route('cleaning.show', $cleaning->id).'" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-eye"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','penempatan','user','tanggal','status'])//biar kebaca html
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

    public function accept($id)
    {
        Cleaning::findOrFail($id)->update([
            'status' => 1
        ]);

      
       return redirect()->back();

    }

    public function decline($id)
    {
        Cleaning::findOrFail($id)->update([
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
        $cleaning = new Cleaning();

        $cleaning->penempatan_id = $request->penempatan_id;
        $cleaning->catatan = $request->catatan;
        $cleaning->created_at = now();
        $cleaning->user_id = auth()->user()->id;

        $folderPath = "foto_clean/";
        if($request->path_foto){
            $img = $request->path_foto;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';

            $file = $folderPath . $fileName;

            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto = 'uploads/foto_clean/'.$fileName;
        }

        if($request->path_foto_2){
            $img = $request->path_foto_2;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';

            $file = $folderPath . $fileName;

            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_2 = 'uploads/foto_clean/'.$fileName;
        }
         
        if($request->path_foto_3){
            $img = $request->path_foto_3;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';

            $file = $folderPath . $fileName;

            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_3 = 'uploads/foto_clean/'.$fileName;
        }

        if($request->path_foto_4){
            $img = $request->path_foto_4;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';

            $file = $folderPath . $fileName;

            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_4 = 'uploads/foto_clean/'.$fileName;
        }

        $cleaning->save();

        $notif = array(
            'message' => 'Berhasil Upload Foto Kebersihan',
            'alert-type' => 'success'
        );

        return redirect()->route('cleaning.index')->with($notif);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cleaning  $cleaning
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cleaning = Cleaning::findOrFail($id);
        $penempatan = Penempatan::all();

        return view('backend.cleaning.show',compact('cleaning','penempatan'));
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
            if($cleaning->path_foto){
                unlink($cleaning->path_foto);
            }
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

        if ($request->path_foto_2) {
            if($cleaning->path_foto_2){
                unlink($cleaning->path_foto_2);

            }
            $img = $request->path_foto_2;
            $folderPath = "foto_clean/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_2 = 'uploads/foto_clean/'.$fileName;
            
        }

        if ($request->path_foto_3) {

            if($cleaning->path_foto_3){
                unlink($cleaning->path_foto_3);

            }
            $img = $request->path_foto_3;
            $folderPath = "foto_clean/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_3 = 'uploads/foto_clean/'.$fileName;
            
        }

        if ($request->path_foto_4) {
            
            if($cleaning->path_foto_4){
                unlink($cleaning->path_foto_4);

            }
            $img = $request->path_foto_4;
            $folderPath = "foto_clean/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cleaning->path_foto_4 = 'uploads/foto_clean/'.$fileName;
            
        }

        $cleaning->update();

        $notif = array(
            'message' => 'Cleaning Berhasil di Update',
            'alert-type' => 'success'
        );

        return redirect()->route('cleaning.index')->with($notif);
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
        if($cleaning->path_foto){
            unlink($cleaning->path_foto);
        }
        if($cleaning->path_foto_2){
            unlink($cleaning->path_foto_2);
        }
        if($cleaning->path_foto_3){
            unlink($cleaning->path_foto_3);
        }
        if($cleaning->path_foto_4){
            unlink($cleaning->path_foto_4);
        }

        $cleaning->delete();

        return response()->json('data berhasil dihapus');
    }
}
