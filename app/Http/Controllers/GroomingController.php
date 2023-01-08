<?php

namespace App\Http\Controllers;

use App\Models\grooming;
use App\Models\karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

            $grooming_karyawan = grooming::where('karyawan_id', auth()->user()->id)->latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($grooming)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($grooming){
                    return ' <a href="'.$grooming->path_foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$grooming->path_foto.'" class="img-fluid" alt="">
                  </a>';
                })
             
                ->addColumn('user', function($grooming){
                    return '<h1 class="badge badge-success">'.$grooming->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($grooming){
                    $result = Carbon::parse($grooming->created_at);
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
             
                ->addColumn('aksi', function($grooming){ //untuk aksi
                    $button = '<div class="btn-group"><a href="'.route('grooming.edit', $grooming->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a><button type="button" onclick="deleteData(`'.route('grooming.destroy', $grooming->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('grooming.acc', $grooming->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('grooming.decline', $grooming->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a></div>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','user','tanggal','status'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($grooming_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('path_foto', function($grooming_karyawan){
                    return ' <a href="'.$grooming_karyawan->path_foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$grooming_karyawan->path_foto.'" class="img-fluid" alt="">
                  </a>';    
                })
                
                
                ->addColumn('user', function($grooming_karyawan){
                    return '<h1 class="badge badge-success">'.$grooming_karyawan->user->name.'</h1>';
                })

                ->addColumn('tanggal', function($grooming_karyawan){
                    $result = Carbon::parse($grooming_karyawan->created_at);
                    return $result;
                })

                ->addColumn('status', function($grooming_karyawan){
                    if($grooming_karyawan->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($grooming_karyawan->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
                ->addColumn('aksi', function($grooming_karyawan){ //untuk aksi
                    $button = '<a href="'.route('grooming.edit', $grooming_karyawan->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a>';
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
        $karyawan = karyawan::all()->pluck('name','id');

        return view('backend.grooming.create', compact('karyawan'));
    }

    public function accept($id)
    {
        $grooming = grooming::findOrFail($id);
        $user = User::where('id',$grooming->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($grooming->status == 0){ //ditolak
            $data_karyawan->grooming += 1;
            $data_karyawan->update();   
        }
        if($grooming->status == 2){ //pending
            $data_karyawan->grooming += 1;
            $data_karyawan->update();
        }else{

        }
        grooming::findOrFail($id)->update([
            'status' => 1
        ]);

        $notif = array(
            'message' => 'Data Grooming Diterima',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);

    }

    public function decline($id)
    {
        $grooming = grooming::findOrFail($id);
        $user = User::where('id',$grooming->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($grooming->status == 1){ //diterima
            $data_karyawan->grooming--;
            $data_karyawan->update();   
        }
        if($grooming->status == 2){ //pending
            $data_karyawan->grooming--;
            $data_karyawan->update();
        }else{

        }

        grooming::findOrFail($id)->update([
            'status' => 0
        ]);

        $notif = array(
            'message' => 'Data Grooming Ditolak',
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
        $data_lama = grooming::where('karyawan_id',$karyawan)->latest()->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') < date('Y-m-d')){
                $grooming = new grooming();
    
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
                }
                  
                $notif = array(
                    'message' => 'Data Grooming Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('grooming.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Data Grooming sudah ada',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
            $grooming = new grooming();
    
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
            }
              
            $notif = array(
                'message' => 'Data Grooming Berhasil di Upload',
                'alert-type' => 'success'
            );
    
            return redirect()->route('grooming.index')->with($notif);
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

        $grooming->karyawan_id = auth()->user()->id;
        $grooming->catatan = $request->catatan;

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

        $notif = array(
            'message' => 'Data Grooming Berhasil di Update',
            'alert-type' => 'info'
        );

        return redirect()->route('grooming.index')->with($notif);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\grooming  $grooming
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grooming = grooming::findOrFail($id);
        $user = User::where('id',$grooming->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();
        if($grooming->status == 1){ //diterima
            $data_karyawan->grooming--;
            $data_karyawan->update();   
        }
    
        unlink($grooming->path_foto);

        $grooming->delete();

        return response()->json('data berhasil dihapus');
    }
}
