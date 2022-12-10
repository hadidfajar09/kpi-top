<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;

class SettingController extends Controller
{

    public function index()
    {
        return view('backend.setting.index');
    }

    public function show()
    {
        return Setting::first();
    }

  
    public function update(Request $request)
    {
        $setting = Setting::first();

        $setting->nama_perusahaan = $request->nama_perusahaan;
        $setting->alamat = $request->alamat;
        $setting->nomor_handphone = $request->nomor_handphone;
        

        if($request->hasFile('path_logo')){
            $image = $request->file('path_logo');
            $nama = 'logo-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/img/'),$nama);

            $setting->path_logo = 'img/'.$nama;
        }

        if($request->hasFile('path_pelanggan')){
            $image = $request->file('path_pelanggan');
            $nama = 'pelanggan-'.date('Y-m-dHis').'.'. $image->getClientOriginalExtension();

            $image->move(public_path('/img/'),$nama);

            $setting->path_pelanggan = 'img/'.$nama;
        }

        $setting->update();

        return response()->json('data berhasil di update',200);
    }

  
}
