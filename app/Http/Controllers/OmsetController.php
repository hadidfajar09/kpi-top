<?php

namespace App\Http\Controllers;

use App\Models\Omset;
use App\Models\User;
use App\Models\Penempatan;
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

            $omset_karyawan = Omset::where('karyawan_id', auth()->user()->id)->latest()->get();


            if(auth()->user()->level == 0 || auth()->user()->level == 3){
                
                // $karyawan = karyawan::where('id',$omset->karyawan_id)->get();
                // $penempatan = Penempatan::where('id', $karyawan->penempatan_id)->first();
                return datatables()
                ->of($omset)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('sales', function($omset){
                    return '<span class="badge badge-success">'.$omset->user->name.'</span>';
                })
                ->addColumn('outlet', function($omset){
                    return '<span class="badge badge-dark">'.$omset->user->data_karyawan->penempatan->nama.'</span>';
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

                ->addColumn('status', function($omset){
                    if($omset->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($omset->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
             
                ->addColumn('aksi', function($omset){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('omset.update', $omset->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('omset.destroy', $omset->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('omset.acc', $omset->id).'" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i></a><a href="'.route('omset.decline', $omset->id).'" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i></a> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','sales','nominal','status','outlet'])//biar kebaca html
                ->make(true);
            }else{
                return datatables()
                ->of($omset_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('sales', function($omset_karyawan){
                    return '<span class="badge badge-success">'.$omset_karyawan->user->name.'</span>';
                })
                ->addColumn('outlet', function($omset_karyawan){
                    return '<span class="badge badge-dark">'.$omset_karyawan->user->data_karyawan->penempatan->nama.'</span>';
                })
                // ->addColumn('user', function($omset_karyawan){
                //     return '<span class="badge badge-primary">'.$omset_karyawan->user->name.'</span>';
                // })
                ->addColumn('nominal', function($omset_karyawan){
                    return 'Rp ' . formatUang($omset_karyawan->nominal);
                })
                ->addColumn('tanggal_setor', function($omset_karyawan){
                    return formatTanggal($omset_karyawan->tanggal_setor);
                })

                ->addColumn('status', function($omset){
                    if($omset->status == 0){
                        return '<span class="badge badge-danger">Ditolak</span>';
      
                    }else if($omset->status == 1){
                      return '<span class="badge badge-success">Diterima</span>';
                    }else{
                        return '<span class="badge badge-light">Pending</span>';

                    }
                })
             
                ->addColumn('aksi', function($omset_karyawan){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('omset.update', $omset_karyawan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','sales','nominal','omset','status','outlet'])//biar kebaca html
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
            'catatan' => 'sometimes',
            'nominal' => 'required',
        ]);
        $karyawan_id = auth()->user()->karyawan_id;
        $data_karyawan = karyawan::where('id',$karyawan_id)->first();

        $omset = new Omset();

        $omset->tanggal_setor = $request->tanggal_setor;
        $omset->karyawan_id = auth()->user()->id;
        $omset->catatan = $request->catatan;
        $omset->nominal = $request->nominal;
        $omset->penempatan_id = $data_karyawan->penempatan_id;
        // $omset->user_id = auth()->user()->id;

        $omset->save();

        $penempatan_nominal = Penempatan::where('id',$data_karyawan->penempatan_id)->first();

        if($request->nominal){

            $penempatan_nominal->nominal += $request->nominal;
            $penempatan_nominal->update();
        }


        return redirect()->route('omset.index');
    }

    public function accept($id)
    {
        $omset = Omset::findOrFail($id);
        $user = User::where('id',$omset->karyawan_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($omset->status == 0){ //ditolak
            $data_karyawan->omset += 1;
            $data_karyawan->update();   
        }
        if($omset->status == 2){ //pending
            $data_karyawan->omset += 1;
            $data_karyawan->update();
        }else{

        }
        Omset::findOrFail($id)->update([
            'status' => 1
        ]);

        $notif = array(
            'message' => 'Data Omset Diterima',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);

    }

    public function decline($id)
    {
        $omset = Omset::findOrFail($id);
        $user = User::where('id',$omset->user_id)->first(); //ambil user 17
        $data_karyawan = karyawan::where('id',$user->karyawan_id)->first();

        if($omset->status == 1){ //diterima
            $data_karyawan->omset--;
            $data_karyawan->update();   
        }
        if($omset->status == 2){ //pending
            $data_karyawan->omset--;
            $data_karyawan->update();
        }else{

        }

        Omset::findOrFail($id)->update([
            'status' => 0
        ]);

        $notif = array(
            'message' => 'Data Omset Ditolak',
            'alert-type' => 'success'
        );

      
       return redirect()->back()->with($notif);
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
    public function update(Request $request, $id)
    {
        $omset = Omset::find($id);
        $omset->tanggal_setor = $request->tanggal_setor;
        $omset->karyawan_id = auth()->user()->id;
        $omset->catatan = $request->catatan;
        $omset->nominal = $request->nominal;

        $omset->update();

        return response()->json('Omset Berhasil Disimpan', 200);
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
