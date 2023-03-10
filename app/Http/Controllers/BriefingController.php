<?php

namespace App\Http\Controllers;

use App\Models\Briefing;
use App\Models\Penempatan;
use App\Models\User;
use App\Models\karyawan;
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

            $briefing_karyawan = Briefing::where('user_id', auth()->user()->id)->latest()->get();



            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                return datatables()
                ->of($briefing)//source
                ->addIndexColumn() //untuk nomer
                // ->addColumn('path_foto', function($briefing){
                //     return '<img src="'.$briefing->path_foto.' " width="150">';
                // })
                ->addColumn('select_all', function($briefing){
                    return '<input type="checkbox" name="id_briefing[]" value="'.$briefing->id.'">';
                })
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
                ->rawColumns(['aksi','path_foto','penempatan','user','tanggal','status','select_all'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($briefing_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('select_all', function($briefing_karyawan){
                    return '<input type="checkbox" name="id_briefing[]" value="'.$briefing_karyawan->id.'">';
                })
                ->addColumn('path_foto', function($briefing_karyawan){
                    return ' <a href="'.$briefing_karyawan->path_foto.'" data-toggle="lightbox" class="col-sm-4">
                    <img src="'.$briefing_karyawan->path_foto.'" class="img-fluid" alt="">
                  </a>';    
                })
                
                
                ->addColumn('user', function($briefing_karyawan){
                    return '<h1 class="badge badge-success">'.$briefing_karyawan->user->name.'</h1>';
                })

                ->addColumn('penempatan', function($briefing_karyawan){
                    return '<h1 class="badge badge-dark">'.$briefing_karyawan->penempatan->nama.'</h1>';
                })

                ->addColumn('tanggal', function($briefing_karyawan){
                    $result = Carbon::parse($briefing_karyawan->created_at);
                    return $result;
                })

                ->addColumn('status', function($briefing_karyawan){
                    if($briefing_karyawan->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($briefing_karyawan->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
                ->addColumn('aksi', function($briefing_karyawan){ //untuk aksi
                    $button = '<a href="'.route('briefing.edit', $briefing_karyawan->id).'" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></a>';
                   return $button;
                })
                ->rawColumns(['aksi','path_foto','user','tanggal','status','penempatan','select_all'])//biar kebaca html
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
        $briefing = Briefing::findOrFail($id);
        $user = User::where('id',$briefing->user_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($briefing->status == 0){ //ditolak
            $data_karyawan->briefing += 1;
            $data_karyawan->update();   
        }
        if($briefing->status == 2){ //pending
            $data_karyawan->briefing += 1;
            $data_karyawan->update();
        }else{

        }

        Briefing::findOrFail($id)->update([
            'status' => 1
        ]);

        $notif = array(
            'message' => 'Data Briefing Diterima',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);

    }

    public function decline($id)
    {
        $briefing = Briefing::findOrFail($id);
        $user = User::where('id',$briefing->user_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($briefing->status == 1){ //diterima
            $data_karyawan->briefing--;
            $data_karyawan->update();   
        }
        if($briefing->status == 2){ //pending
            $data_karyawan->briefing--;
            $data_karyawan->update();
        }else{

        }

        Briefing::findOrFail($id)->update([
            'status' => 0
        ]);

      
        $notif = array(
            'message' => 'Data Briefing Ditolak',
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

        $karyawan = auth()->user()->id;
        $data_lama = Briefing::where('user_id',$karyawan)->latest()->first();
        $now = Carbon::now();

        if($data_lama){
            if($data_lama->created_at->format('Y-m-d') < date('Y-m-d')){
                $briefing = new Briefing();
    
                $briefing->user_id = $karyawan;
                $briefing->penempatan_id = $request->penempatan_id;
                $briefing->catatan = $request->catatan;
        
                if($request->path_foto == NULL){
                    $notif = array(
                        'message' => 'Anda belum memasukkan foto',
                        'alert-type' => 'error'
                    );
        
                    return redirect()->back()->with($notif);
                }else{
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
                }
                  
                $notif = array(
                    'message' => 'Data Briefing Berhasil di Upload',
                    'alert-type' => 'success'
                );
        
                return redirect()->route('briefing.index')->with($notif);
            }else{
    
                $notif = array(
                    'message' => 'Data Briefing sudah ada',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }
        }else{
            $briefing = new Briefing();
    
            $briefing->user_id = $karyawan;
            $briefing->penempatan_id = $request->penempatan_id;
            $briefing->catatan = $request->catatan;
    
            if($request->path_foto == NULL){
                $notif = array(
                    'message' => 'Anda belum memasukkan foto',
                    'alert-type' => 'error'
                );
    
                return redirect()->back()->with($notif);
            }else{
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
            }
              
            $notif = array(
                'message' => 'Data Briefing Berhasil di Upload',
                'alert-type' => 'success'
            );
    
            return redirect()->route('briefing.index')->with($notif);
        }
        

      
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

        $notif = array(
            'message' => 'Data Briefing Berhasil di Update',
            'alert-type' => 'info'
        );

        return redirect()->route('briefing.index')->with($notif);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Briefing  $briefing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $briefing = Briefing::findOrFail($id);
        $user = User::where('id',$briefing->user_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();
        if($briefing->status == 1){ //diterima
            $data_karyawan->briefing--;
            $data_karyawan->update();   
        }
        unlink($briefing->path_foto);

        $briefing->delete();

        return response()->json('data berhasil dihapus');
    }

    public function acceptSelected(Request $request)
    {
        foreach($request->id_briefing  as $id){
            $briefing = Briefing::find($id);

            $briefing->status = 1;

            $briefing->update();
        }
        return response()->json('briefing berhasil diterima');
        
    }
}
