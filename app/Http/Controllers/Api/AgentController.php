<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgentResource;
use App\Http\Resources\DistribusiResource;
use App\Http\Resources\PangkalanDetailResource;
use App\Models\Distribusi;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function loginAgent(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $agent = User::where('email', $request->email)->where('level',1)->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Agent tidak dikenali'

            ], 401);
        }
        $agent->tokens()->delete();
        $token = $agent->createToken($request->email)->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Anda Login Sebagai Agent',
            'token' => $token,
            'agent' => new AgentResource($agent)
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Logout Agent',
            'data' => $user

        ], 200);
    }

    public function getAgent()
    {

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Mendapatkan Agent login sekarang',
            'data' => new AgentResource($user)

        ], 200);
    }

    public function getDistribusi()
    {
            $id_agent = auth()->user()->id;
        
            $distribusi = Distribusi::
            where('id_agent',$id_agent)
            ->orderBy('id','desc')->get();

            $pangkalan = User::join('desas', 'users.id_desa', 'desas.id')
            ->join('kecamatans', 'users.id_kecamatan', 'kecamatans.id')
            ->select('users.*', 'desas.nama_desa', 'kecamatans.nama_kecamatan')->where('id_agent',$id_agent)->get();

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
                    'message' => 'Tidak ada Daftar Distribusi Agen',
        
                ], 401);
            }


    }

    public function ubahStatusByAgent(Request $request)
    {
        $id_distribusi = $request->input('id_distribusi');
        $status = $request->input('status');
        $id_agent = auth()->user()->id;

        if($status == 1){
            $distribusi = Distribusi::where('id', $id_distribusi)->where('id_agent', $id_agent)->where('status',0)->first();
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

    public function getDistribusiById(Request $request)
    {
        $id = $request->get('id_distribusi');

        $distribusi = Distribusi::where('id',$id)->first();

        if($distribusi){
            return response()->json([
                'success' => true,
                'message' => 'Detail ditemukan',
                'distribusi' => new DistribusiResource($distribusi)
    
            ], 200);
        }else{   
            return response()->json([
                'success' => false,
                'message' => 'Detail tidak ditemukan',
    
            ], 401);
        }
    }

    public function getDistribusiByIdPangkalan(Request $request)
    {
        
        $agent = auth()->user()->id;
        $distribusi = Distribusi::where('id_agent',$agent)->orderBy('id','desc')->get();

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

    public function getPangkalanById(Request $request)
    {
        $id = $request->get('id_pangkalan');

        $pangkalan = User::where('id',$id)->where('level',2)->first();


        if($pangkalan){
            return response()->json([
                'success' => true,
                'message' => 'Pangkalan ditemukan',
                'pangkalan' => new PangkalanDetailResource($pangkalan) ,
               
    
            ], 200);
        }else{   
            return response()->json([
                'success' => false,
                'message' => 'Pangkalan tidak ditemukan',
    
            ], 401);
        }
    }

    public function getSliderByAgent()
    {
        $slider = Slider::where('level',1)->limit(3)->orderBy('id','desc')->get();

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
}
