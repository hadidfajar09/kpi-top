<?php

namespace App\Http\Controllers;

use App\Models\Briefing;
use App\Models\Absensi;
use App\Models\Cleaning;
use App\Models\Distribusi;
use App\Models\grooming;
use App\Models\karyawan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {

        $grooming=grooming::whereDate('created_at', date('Y-m-d'))->get()->count();
        $briefing=Briefing::whereDate('created_at', date('Y-m-d'))->get()->count();
        $bersih=Cleaning::whereDate('created_at', date('Y-m-d'))->get()->count();
        $karyawan=karyawan::get()->count();

      
        if (auth()->user()->level == 0) {
            $absensi=Absensi::count();
            $tanggal_awal = date('Y-m-01');
            $tanggal_akhir = date('Y-m-d');
            
            $data_tanggal = array();
            $stock = 0;
            $total_stock = array();
    
            //stock masuk
            $stock_masuk = 0;
            $total_stock_masuk = array();
    
            //stock keluar
            $stock_keluar = 0;
            $total_stock_keluar = array();

             //cleaning
             $cleaning = 0;
             $total_cleaning = array();
             //Absen
             $absen = 0;
             $total_absen = array();
    
            while(strtotime($tanggal_awal) <= strtotime($tanggal_akhir)){
                $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);
    
                $distribusi = grooming::where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $transaksi = Briefing::where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $cleaning = Cleaning::where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $absen = Absensi::where('created_at', 'LIKE', "%$tanggal_awal%")->count();
    
                $stock = $distribusi - $transaksi;
                $total_stock[] += $stock;
    
                $stock_masuk = $distribusi;
                $total_stock_masuk[] += $stock_masuk;
    
                $stock_keluar = $transaksi;
                $total_stock_keluar[] += $stock_keluar;

                $cleaning = $cleaning;
                $total_cleaning[] += $cleaning;

                $absen = $absen;
                $total_absen[] += $absen;
                
                
                $tanggal_awal = date('Y-m-d', strtotime("+1 day",strtotime($tanggal_awal)));
    
            }
    
            $tanggal_awal = date('Y-m-01');
            
                return view('backend.dashboard',compact('tanggal_awal','tanggal_akhir','data_tanggal','total_stock','total_stock_masuk','total_stock_keluar','briefing','grooming','karyawan','bersih','total_cleaning','total_absen','absensi'));
           
        }else{
            $user = auth()->user()->id;
            $absensi=Absensi::where('karyawan_id',$user)->count();
            $tanggal_awal = date('Y-m-01');
            $tanggal_akhir = date('Y-m-d');
            
            $data_tanggal = array();
            $stock = 0;
            $total_stock = array();
    
            //stock masuk
            $stock_masuk = 0;
            $total_stock_masuk = array();
    
            //stock keluar
            $stock_keluar = 0;
            $total_stock_keluar = array();
            
            //cleaning
            $cleaning = 0;
            $total_cleaning = array();
            //Absen
            $absen = 0;
            $total_absen = array();
    
            while(strtotime($tanggal_awal) <= strtotime($tanggal_akhir)){
                $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);
    
                $distribusi = grooming::where('karyawan_id',$user)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $transaksi = Briefing::where('user_id',$user)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $cleaning = Cleaning::where('user_id',$user)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
                $absen = Absensi::where('karyawan_id',$user)->where('created_at', 'LIKE', "%$tanggal_awal%")->count();
    
                $stock = $distribusi - $transaksi;
                $total_stock[] += $stock;
    
                $stock_masuk = $distribusi;
                $total_stock_masuk[] += $stock_masuk;
    
                $stock_keluar = $transaksi;
                $total_stock_keluar[] += $stock_keluar;
               
                $cleaning = $cleaning;
                $total_cleaning[] += $cleaning;

                $absen = $absen;
                $total_absen[] += $absen;
                
                $tanggal_awal = date('Y-m-d', strtotime("+1 day",strtotime($tanggal_awal)));
    
            }
    
            $tanggal_awal = date('Y-m-01');
            
                return view('backend.dashboard_2',compact('tanggal_awal','tanggal_akhir','data_tanggal','total_stock','total_stock_masuk','total_stock_keluar','briefing','grooming','karyawan','cleaning','bersih','total_cleaning','total_absen','absensi'));
           
        }

       
        
    }

    public function profile()
    {   
        $profile = Auth::user();
        return view('backend.user.profile',compact('profile'));
    }

    public function show($id)
    {   
        $user = User::find($id);
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {

        $profile = Auth::user();

        $data_karyawan_id = Auth::user()->karyawan_id;

        $data_karyawan = karyawan::where('id',$data_karyawan_id)->first();


        $profile->name = $request->name;
      
        
        if($request->has('password') && $request->password != ""){
            if(Hash::check($request->old_password, $profile->password)){
                if($request->password == $request->password_confirmation){
                    $profile->password = bcrypt($request->password);
                } else{
                    return response()->json('Password konfirmasi tidak sesuai',422);
                }
            }
            else{
                return response()->json('Password lama tidak sesuai',422);
            }
        }

        if($request->hasFile('profile_photo_path')){
            $image = $request->file('profile_photo_path');
            $nama = 'logo-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/img/'),$nama);

            $profile->profile_photo_path = 'img/'.$nama;
            $data_karyawan->foto = 'img/'.$nama;
        }


        $profile->update();
        $data_karyawan->update();

        return response()->json($profile,200);
    }

    public function index()
    {
        $karyawan = karyawan::orderBy('name','asc')->pluck('name','id');
        return view('backend.user.index',compact('karyawan'));
    }

    public function store(Request $request)
    {
        $user = User::latest()->first() ?? new User();
        $request->kode_user = 'U'. tambahNolDepan((int)$user->id+1, 3);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->kode_user = $request->kode_user;
        $user->karyawan_id = $request->karyawan_id;
        $user->password = bcrypt($request->password);
        $user->level = $request->level;
        $user->profile_photo_path = 'img/profile.png';

        $user->save();

        $karyawan = Karyawan::where('id', $user->karyawan_id)->first();
        $karyawan->user_id = $user->id;
        $karyawan->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function data()
    {
        $user = User::isNotAdmin()->orderBy('name','asc')->get();

        return datatables()
            ->of($user)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('kode_user', function($user){
                return '<h1 class="badge badge-info">'.$user->kode_user.'</h1>';
            })
            ->addColumn('data_karyawan', function($user){
                return '<h1 class="badge badge-dark">'.$user->data_karyawan->name.'</h1>';
            })
            ->addColumn('level', function($user){
                if($user->level == 4){
                    return '<span class="badge badge-danger">LEADER</span>';
  
                }else if($user->level == 3){
                  return '<span class="badge badge-success">HRD</span>';
                }else if($user->level == 5){
                    return '<span class="badge badge-info">KARYAWAN</span>';
                }else{
                    return '<span class="badge badge-warning">SUPER ADMIN</span>';
                }
            })
            ->addColumn('aksi', function($user){ //untuk aksi
                $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('user.update', $user->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('user.destroy', $user->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('user.destroy', $user->id).'" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-eye"></i></a> </div>';
               return $button;
            })
            ->rawColumns(['aksi','kode_user','level','data_karyawan'])//biar kebaca html
            ->make(true);
    }

    public function showUser($id)
    {   
        $user = User::find($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->email = $request->email;
        $user->karyawan_id = $request->karyawan_id;
        $user->name = $request->name;
        $user->level = $request->level;
        
        if($request->has('password') && $request->password != ""){
            $user->password = bcrypt($request->password);
        }

        if($request->hasFile('profile_photo_path')){
            $image = $request->file('profile_photo_path');
            $nama = 'logo-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/img/'),$nama);

            $user->profile_photo_path = 'img/'.$nama;
        }

        $user->update();

        return response()->json($user,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
 
        $user->delete();

        return response()->json('data berhasil dihapus');
    }
}
