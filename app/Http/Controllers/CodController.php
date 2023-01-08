<?php

namespace App\Http\Controllers;

use App\Models\Cod;
use App\Models\User;
use App\Models\karyawan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CodController extends Controller
{
    public function index()
    {
        
        return view('backend.cod.index');
    }

    public function data()
    {
        $cod = Cod::
            latest()->get();

            $cod_karyawan = Cod::where('karyawan_id', auth()->user()->id)->latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($cod)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($cod){
                    return ' <a href="'.$cod->path_foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$cod->path_foto.'" class="img-fluid" alt="">
                  </a>';
                })
             
                ->addColumn('user', function($cod){
                    return '<h1 class="badge badge-success">'.$cod->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($cod){
                    $result = Carbon::parse($cod->created_at);
                    return $result;
                })

                ->addColumn('status', function($cod){
                    if($cod->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($cod->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
             
                ->addColumn('aksi', function($cod){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('cod.edit', $cod->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('cod.destroy', $cod->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('cod.acc', $cod->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('cod.decline', $cod->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','user','tanggal','status'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($cod_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($cod_karyawan){
                    return ' <a href="'.$cod_karyawan->path_foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$cod_karyawan->path_foto.'" class="img-fluid" alt="">
                  </a>';    
                })
                
                
                ->addColumn('user', function($cod_karyawan){
                    return '<h1 class="badge badge-success">'.$cod_karyawan->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($cod_karyawan){
                    $result = Carbon::parse($cod_karyawan->created_at);
                    return $result;
                })

                ->addColumn('status', function($cod_karyawan){
                    if($cod_karyawan->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($cod_karyawan->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
                ->addColumn('aksi', function($cod_karyawan){ //untuk aksi
                    $button = '<a href="'.route('grooming.edit', $cod_karyawan->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','user','tanggal','status'])//biar kebaca html
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

        return view('backend.cod.create');
    }

    public function accept($id)
    {
        $cod = Cod::findOrFail($id);
        $user = User::where('id',$cod->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($cod->status == 0){ //ditolak
            $data_karyawan->cod += 1;
            $data_karyawan->update();   
        }
        if($cod->status == 2){ //pending
            $data_karyawan->cod += 1;
            $data_karyawan->update();
        }else{

        }
        Cod::findOrFail($id)->update([
            'status' => 1
        ]);

        $notif = array(
            'message' => 'Data COD Diterima',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);

    }

    public function decline($id)
    {
        $cod = Cod::findOrFail($id);
        $user = User::where('id',$cod->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($cod->status == 1){ //diterima
            $data_karyawan->cod--;
            $data_karyawan->update();   
        }
        if($cod->status == 2){ //pending
            $data_karyawan->cod--;
            $data_karyawan->update();
        }else{

        }

        Cod::findOrFail($id)->update([
            'status' => 0
        ]);

        $notif = array(
            'message' => 'Data COD Ditolak',
            'alert-type' => 'error'
        );

      
       return redirect()->back()->with($notif);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'karyawan_id' => 'required|unique:groomings,karyawan_id,NULL,id' . Carbon::today()->toDateString(),
        // ],[
        //     'karyawan_id.unique' => 'Anda sudah Grooming Hari ini'
        // ]);

        // $validated =  $request->validate([
        //     'karyawan_id' => function($attribute, $value, $fail) {
        //         $today = Carbon::now()->startOfDay();
        //         $record = grooming::where('karyawan_id', $value)
        //             ->where('created_at', '>=', $today)
        //             ->first();
        //         if ($record) {
        //             $fail('Error message');
        //         }
        //     }
        // ]);

        $karyawan = auth()->user()->id;
        $data_lama = Cod::where('karyawan_id',$karyawan)->latest()->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') < date('Y-m-d')){
                $cod = new Cod();
    
                $cod->karyawan_id = $karyawan;
                $cod->catatan = $request->catatan;
        
                if($request->path_foto == NULL){
                    $notif = array(
                        'message' => 'Anda belum memasukkan foto',
                        'alert-type' => 'error'
                    );
        
                    return redirect()->back()->with($notif);
                }else{
                    $img = $request->path_foto;
                    $folderPath = "cod/";
                    
                    $image_parts = explode(";base64,", $img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = uniqid() . '.png';
                    
                    $file = $folderPath . $fileName;
                    
                    Storage::disk('public_uploads')->put($file, $image_base64);
        
                    $cod->path_foto = 'uploads/cod/'.$fileName;
                    $cod->save();
                }
                  
                $notif = array(
                    'message' => 'Data COD Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('cod.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Data COD sudah ada',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
            $grooming = new Cod();
    
            $grooming->karyawan_id = $karyawan;
            $grooming->catatan = $request->catatan;
    
            if($request->path_foto == NULL){
                $notif = array(
                    'message' => 'Anda belum memasukkan foto',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }else{
                $img = $request->path_foto;
                $folderPath = "cod/";
                
                $image_parts = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = uniqid() . '.png';
                
                $file = $folderPath . $fileName;
                
                Storage::disk('public_uploads')->put($file, $image_base64);
    
                $grooming->path_foto = 'uploads/cod/'.$fileName;
                $grooming->save();
            }
              
            $notif = array(
                'message' => 'Data COD Berhasil di Upload',
                'alert-type' => 'success'
            );
    
            return redirect()->route('cod.index')->with($notif);
        }
        

       


        
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
        $cod = Cod::findOrfail($id);
        $karyawan = karyawan::all();

        return view('backend.cod.edit',compact('karyawan','cod'));
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
        $cod = Cod::find($id);

        $cod->karyawan_id = auth()->user()->id;
        $cod->catatan = $request->catatan;

        if ($request->path_foto) {
            unlink($cod->path_foto);
            $img = $request->path_foto;
            $folderPath = "cod/";
            
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            
            $file = $folderPath . $fileName;
            
            Storage::disk('public_uploads')->put($file, $image_base64);

            $cod->path_foto = 'uploads/cod/'.$fileName;
            
        }

        $cod->update();

        $notif = array(
            'message' => 'Data cod Berhasil di Update',
            'alert-type' => 'info'
        );

        return redirect()->route('cod.index')->with($notif);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cod = Cod::findOrFail($id);
        $user = User::where('id',$cod->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();
        if($cod->status == 1){ //diterima
            $data_karyawan->cod--;
            $data_karyawan->update();   
        }
    
        unlink($cod->path_foto);

        $cod->delete();

        return response()->json('data berhasil dihapus');
    }
}
