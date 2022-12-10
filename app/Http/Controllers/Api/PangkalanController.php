<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistribusiResource;
use App\Http\Resources\PangkalanResource;
use App\Http\Resources\TransaksiResource;
use App\Models\Pangkalan;
use App\Models\Pelanggan;
use App\Models\Distribusi;
use App\Models\Slider;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PangkalanController extends Controller
{
    public function loginPangkalan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        

        $pangkalan = User::where('email', $request->email)->where('level',2)
        ->join('desas', 'users.id_desa', 'desas.id')
        ->join('kecamatans', 'users.id_kecamatan', 'kecamatans.id')
        
        // ->join('subcategories', 'produks.subnama_toko_id', 'subcategories.id')
        ->select('users.*', 'desas.nama_desa', 'kecamatans.nama_kecamatan')
        ->first();

        $nama_agent = User::where('id', $pangkalan->id_agent)->first();

        if (!$pangkalan || !Hash::check($request->password, $pangkalan->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Pangkalan tidak dikenali'

            ], 401);
        }
        $pangkalan->tokens()->delete();
        $token = $pangkalan->createToken($request->email)->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Anda Login Sebagai Pangkalan',
            'token' => $token,
            'data' => [  'pangkalan' => new PangkalanResource($pangkalan),
                         'nama_agent' => $nama_agent->name
            ]
            
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Logout Pangkalan',
            'data' => $user

        ], 200);
    }

    public function getPangkalan()
    {

        $pangkalan = Auth::user();

        $nama_agent = User::where('id', $pangkalan->id_agent)->first();

        return response()->json([
            'success' => true,
            'message' => 'Mendapatkan Pangkalan login sekarang',
            'data' =>  new PangkalanResource($pangkalan),
            'nama_agent' => $nama_agent->name
            

        ], 200);
    }

    public function scanPelanggan(Request $request)
    {
        $qr_code = $request->input('qrcode');

        $pelanggan = Pelanggan::where('qrcode',$qr_code)->first();

        if($pelanggan == null){
            return response()->json([
                'error' => 'Data tidak terdaftar',
            ]);
        }else{
            $transaksi_terakhir = Transaksi::where('id_pelanggan', $pelanggan->id)->latest()->first();
          
            if($transaksi_terakhir){
                $scan4hari = date('Y-m-d', strtotime("+5 day",strtotime($transaksi_terakhir->created_at)));
                if(date(now() >= $scan4hari)){
                    if($pelanggan->jumlah_tabung != 0){
                        $transaksi = Transaksi::latest()->first() ?? new Transaksi();
                        // $pangkalan = User::where('id',$transaksi->id_pangkalan)->first();
                        $pangkalan = auth()->user();
        
                        if($pangkalan->stock_tabung != 0){
                            if($pelanggan->id_pangkalan == auth()->user()->id){
        
                                $pelanggan->jumlah_tabung -= 1;
                                $pelanggan->update();
                 
                                 $pangkalan->stock_tabung -= 1;
                                 $pangkalan->update();
             
                                 $request['kode_transaksi'] = 'T'. tambahNolDepan((int)$transaksi->id+1, 8);
                 
                             $transaksi = new Transaksi();
                             $transaksi->kode_transaksi = $request['kode_transaksi'];
                             $transaksi->id_pelanggan = $pelanggan->id;
                     
                             $transaksi->id_pangkalan = auth()->user()->id;
                             $transaksi->tanggal_ambil = now();
                             $transaksi->jumlah_tabung = 1;
                 
                             $transaksi->save();
             
                             return response()->json([
                                 'status' => true,
                                 'message' => 'Berhasil Mengambil Tabung',
                                 'data' => new TransaksiResource($transaksi)
                             ]);
                            }else{
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Tempat Pelanggan tidak sesuai',
                                ]);
                            }
                        }else{
                            return response()->json([
                                'status' => false,
                                'message' => 'Stock Pangkalan anda Habis',
                            ]);
                        }
                            
                    }else{
                        return response()->json([
                            'status' => false,
                            'message' => 'Jatah Tabung Pelanggan anda Habis',
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Datang Lagi 4 Hari',
                    ]);
                }
            }else{
                if($pelanggan->jumlah_tabung != 0){
                    $transaksi = Transaksi::latest()->first() ?? new Transaksi();
                    // $pangkalan = User::where('id',$transaksi->id_pangkalan)->first();
                    $pangkalan = auth()->user();
    
                    if($pangkalan->stock_tabung != 0){
                        if($pelanggan->id_pangkalan == auth()->user()->id){
    
                            $pelanggan->jumlah_tabung -= 1;
                            $pelanggan->update();
             
                             $pangkalan->stock_tabung -= 1;
                             $pangkalan->update();
         
                             $request['kode_transaksi'] = 'T'. tambahNolDepan((int)$transaksi->id+1, 8);
             
                         $transaksi = new Transaksi();
                         $transaksi->kode_transaksi = $request['kode_transaksi'];
                         $transaksi->id_pelanggan = $pelanggan->id;
                 
                         $transaksi->id_pangkalan = auth()->user()->id;
                         $transaksi->tanggal_ambil = now();
                         $transaksi->jumlah_tabung = 1;
             
                         $transaksi->save();
         
                         return response()->json([
                             'status' => true,
                             'message' => 'Berhasil Mengambil Tabung',
                             'data' => new TransaksiResource($transaksi)
                         ]);
                        }else{
                            return response()->json([
                                'status' => false,
                                'message' => 'Tempat Pelanggan tidak sesuai',
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status' => false,
                            'message' => 'Stock Pangkalan anda Habis',
                        ]);
                    }
                        
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Jatah Tabung Pelanggan anda Habis',
                    ]);
                } 
            }
        }


    }

    public function transaksiByPangkalan()
    {
        $pangkalan = auth()->user()->id;

        $transaksi = Transaksi::
        where('id_pangkalan',$pangkalan)->orderBy('id','desc')->get();

        if($transaksi){
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mendapatkan riwayat',
                'data' => TransaksiResource::collection($transaksi),
                'pangkalan' => auth()->user()->name
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan riwayat',
                
            ]);
        }
    }

    public function ubahStatusByPangkalan(Request $request)
    {
        $id_distribusi = $request->input('id_distribusi');
        $status = $request->input('status');
        $pangkalan = auth()->user();

        if($status == 1){
            $distribusi = Distribusi::where('id', $id_distribusi)->where('id_agent', $pangkalan->id_agent)->where('status',0)->first();
            if($distribusi){
                $distribusi->status = 1;
                $distribusi->update();

                $pangkalan = User::where('id',$distribusi->id_pangkalan)->first();
                $pangkalan->stock_tabung += $distribusi->drop_tabung;
                $pangkalan->update(); 

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah status',
                    'data' => new DistribusiResource($distribusi),
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan',
                ], 401);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'status tidak benar',
    
            ], 401);
        }


       
      
        
    }

    public function getSliderByPangkalan()
    {
        $slider = Slider::where('level',2)->limit(3)->orderBy('id','desc')->get();

        if($slider){
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan slider',
                'data' => $slider,
    
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'status tidak benar',
    
            ], 401);
        }
    }

    public function getDistribusi()
    {
            $id_pangkalan = auth()->user()->id;
        
            $distribusi = Distribusi::
            where('id_pangkalan',$id_pangkalan)
            ->orderBy('id','desc')->get();

            $pangkalan = User::join('desas', 'users.id_desa', 'desas.id')
            ->join('kecamatans', 'users.id_kecamatan', 'kecamatans.id')
            ->select('users.*', 'desas.nama_desa', 'kecamatans.nama_kecamatan')->where('id_pangkalan',$id_pangkalan)->get();

            if($distribusi){
                return response()->json([
                    'success' => true,
                    'message' => 'Mendapatkan Daftar Distribusi Agen',
                    'data' => [
                        'distribusi' => DistribusiResource::collection($distribusi),
                        // 'pangkalan' => PangkalanResource::collection($pangkalan) ,
                        // 'nama_agent' => auth()->user()->name
                    ],
                    
                    
        
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Daftar Distribusi Agen',
        
                ], 401);
            }


    }

    public function getDistribusiByIdPangkalan(Request $request)
    {
        
        $pangkalan = auth()->user()->id;
        $distribusi = Distribusi::where('id_pangkalan',$pangkalan)->orderBy('id','desc')->get();

        if($distribusi){
            return response()->json([ 
                'success' => true,
                'message' => 'Distribusi ditemukan',
                'distribusi' => DistribusiResource::collection($distribusi) 
    
            ], 200);
        }else{   
            return response()->json([
                'success' => false,
                'message' => 'Distribusi tidak ditemukan',
    
            ], 401);
        }
    }

    public function requestTabungByPangkalan(Request $request)
    {
        $pangkalan = auth()->user();
        $input_tabung = $request->input('drop_tabung');
        $tanggal_pengantaran = $request->input('tanggal_pengantaran');

        if($input_tabung && $tanggal_pengantaran){
            $distribusi = new Distribusi();
            $distribusi->tanggal_pengantaran = $tanggal_pengantaran;
            $distribusi->drop_tabung = $input_tabung;
      
            $distribusi->id_agent = $pangkalan->id_agent;
            $distribusi->id_pangkalan = $pangkalan->id;
    
            $distribusi->status = 0;
    
            $distribusi->save();

            return response()->json([ 
                'success' => true,
                'message' => 'Request Berhasil Dilakukan',
                'distribusi' => new DistribusiResource($distribusi)
    
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Distribusi Gagal',
            ], 401);
        }




    }
}
