<?php

namespace App\Http\Controllers;

use App\Models\agent;
use App\Models\Distribusi;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.jabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $agent = User::where('level', 1)->orderBy('name','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($agent)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('kode_user', function($agent){
            return '<h1 class="badge badge-info">'.$agent->kode_user.'</h1>';
        })
        ->addColumn('aksi', function($agent){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('agent.update', $agent->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('agent.destroy', $agent->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
           return $button;
        })
        ->rawColumns(['aksi','kode_user'])//biar kebaca html
        ->make(true);

    }else{
    return datatables()
    ->of($agent)//source
    ->addIndexColumn() //untuk nomer
    ->addColumn('kode_user', function($agent){
        return '<h1 class="badge badge-info">'.$agent->kode_user.'</h1>';
    })
    ->addColumn('aksi', function($agent){ //untuk aksi
        $button = '-';
       return $button;
    })
    ->rawColumns(['aksi','kode_user'])//biar kebaca html
    ->make(true);
}

    }
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
        $agent = User::latest()->first() ?? new User();
        $request->kode_user = 'A'. tambahNolDepan((int)$agent->id+1, 3);

        $agent = new User();
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->kode_user = $request->kode_user;
        $agent->password = bcrypt($request->password);
        $agent->level = 1;

        $agent->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agent = User::find($id);
        return response()->json($agent);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agent = User::find($id);
        $agent->name = $request->name;
        $agent->email = $request->email;

        if($request->has('password') && $request->password != ""){
            $agent->password = bcrypt($request->password);
        }
        $agent->update();

        return response()->json('Agent Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agent = User::find($id);

        $pangkalan = User::where('id_agent', $agent->id)->get();
        $distribusi = Distribusi::where('id_agent',$id)->get();

        
        foreach ($pangkalan as $row) {
            $row->delete();
        }

        foreach ($distribusi as $row) {
            $row->delete();
        }


        $agent->delete();

        return response()->json('data berhasil dihapus');
    }
}
