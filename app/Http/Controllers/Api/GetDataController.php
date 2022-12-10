<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PangkalanResource;
use App\Http\Resources\PelangganResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataController extends Controller
{
    public function getPelanggan()
    {
        $key = DB::table('pelanggans')
        ->join('desas', 'pelanggans.id_desa', 'desas.id')
        ->join('kecamatans', 'pelanggans.id_kecamatan', 'kecamatans.id')
        ->join('pekerjaans', 'pelanggans.id_pekerjaan', 'pekerjaans.id')
        ->join('penghasilans', 'pelanggans.id_penghasilan', 'penghasilans.id')
        ->join('users', 'pelanggans.id_pangkalan', 'users.id')
        ->select('pelanggans.*', 'desas.nama_desa', 'kecamatans.nama_kecamatan', 'kecamatans.harga_tabung','pekerjaans.nama_pekerjaan', 'penghasilans.nominal_gaji','users.name')
        ->orderBy('id','desc')->get();
        return response()->json([
            'status' => 'ok',
            'reply' => 'List Pelanggan ditemmukan',
            'totalResults' => $key->count(),
            'Pelanggan' => PelangganResource::collection($key)
        ], 200);
    }

    public function getPangkalanByAgent()
    {
        $id_agent = auth()->user()->id;

        $pangkalan = User::where('id_agent',$id_agent)->get();

        if($pangkalan){
            return response()->json([
                'status' => TRUE,
                'msg' => 'Pangkalan Berhasil ditemukan',
                'data' => PangkalanResource::collection($pangkalan)
            ], 200);
        }else{
            return response()->json([
                'status' => FALSE,
                'msg' => 'Pangkalan Agent ini tidak ditemukan'
            ], 404);
        }

    }


}
