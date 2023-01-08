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

            $omset_karyawan = Omset::where('karyawan_id', auth()->user()->id)->latest()->get();


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
                ->of($omset_karyawan)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('sales', function($omset_karyawan){
                    return '<span class="badge badge-success">'.$omset_karyawan->user->name.'</span>';
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
             
                ->addColumn('aksi', function($omset_karyawan){ //untuk aksi
                    $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('omset.update', $omset_karyawan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','sales','nominal'])//biar kebaca html
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
