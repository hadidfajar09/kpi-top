<?php

namespace App\Http\Controllers;

use App\Exports\ExportTransaksi;
use App\Models\Pangkalan;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksi = Transaksi::all();
        return view('backend.transaksi.index',compact('transaksi'));
    }

    public function transaksiExport(Request $request)
    {
        return Excel::download(new ExportTransaksi, 'transaksi.xlsx');

        
    }

    public function data()
    {
        $transaksi = Transaksi::orderBy('id','desc')->get();

            if (auth()->user()->level == 0 || auth()->user()->level == 3) {
                return datatables()
                ->of($transaksi)//source
                ->addIndexColumn() //untuk nomer
                ->addColumn('kode_transaksi', function($transaksi){
                    return '<span class="badge badge-success">'.$transaksi->kode_transaksi.'</span>';
                })
                ->addColumn('nama_pelanggan', function($transaksi){
                    $nama_pelanggan = Pelanggan::where('id',$transaksi->id_pelanggan)->first();
                    return $nama_pelanggan->nama_pelanggan;
                })
                ->addColumn('nama_pangkalan', function($transaksi){
                    $nama_pangkalan = User::where('id', $transaksi->id_pangkalan)->first();
                    return $nama_pangkalan->name;
                })
                ->addColumn('harga_tabung', function($transaksi){
                    // $pangkalan = Pangkalan::where('id',$transaksi->id_pangkalan)
                    return 'Rp ' . formatUang($transaksi->pelanggan->kecamatan->harga_tabung);
                })
                ->addColumn('tanggal_ambil', function($transaksi){
                    return formatTanggal($transaksi->tanggal_ambil);
                })
                ->addColumn('aksi', function($transaksi){ //untuk aksi
                    $button = '<button type="button" onclick="deleteData(`'.route('transaksi.destroy', $transaksi->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button> </div>';
                   return $button;
                })
                ->rawColumns(['aksi','kode_transaksi'])//biar kebaca html
                ->make(true);
            }else{
               
                    return datatables()
                    ->of($transaksi)//source
                    ->addIndexColumn() //untuk nomer
                    ->addColumn('kode_transaksi', function($transaksi){
                        return '<span class="badge badge-success">'.$transaksi->kode_transaksi.'</span>';
                    })
                    ->addColumn('nama_pelanggan', function($transaksi){
                        $nama_pelanggan = Pelanggan::where('id',$transaksi->id_pelanggan)->first();
                        return $nama_pelanggan->nama_pelanggan;
                    })
                    ->addColumn('nama_pangkalan', function($transaksi){
                        $nama_pangkalan = User::where('id', $transaksi->id_pangkalan)->first();
                        return $nama_pangkalan->name;
                    })
                    ->addColumn('harga_tabung', function($transaksi){
                        // $pangkalan = Pangkalan::where('id',$transaksi->id_pangkalan)
                        return 'Rp ' . formatUang($transaksi->pelanggan->kecamatan->harga_tabung);
                    })
                    ->addColumn('tanggal_ambil', function($transaksi){
                        return formatTanggal($transaksi->tanggal_ambil);
                    })
                    ->addColumn('aksi', function($transaksi){ //untuk aksi
                        $button = '-';
                       return $button;
                    })
                    ->rawColumns(['aksi','kode_transaksi'])//biar kebaca html
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
        $pelanggan = Pelanggan::where('qrcode',$request->qr_code)->first();

        if($pelanggan == null){
            return response()->json([
                'error' => 'Data tidak terdaftar',
            ]);
        }else{
            if($pelanggan->jumlah_tabung != 0){
                $pelanggan->jumlah_tabung -= 1;
                $pelanggan->update();
    
                $transaksi = Transaksi::latest()->first() ?? new Transaksi();
                $request['kode_transaksi'] = 'T'. tambahNolDepan((int)$transaksi->id+1, 8);
    
                $transaksi = new Transaksi();
                $transaksi->kode_transaksi = $request['kode_transaksi'];
                $transaksi->id_pelanggan = $pelanggan->id;
        
                $transaksi->id_pangkalan = auth()->user()->id;
                $transaksi->tanggal_ambil = now();
                $transaksi->jumlah_tabung = 1;
    
                $transaksi->save();
                
                $pangkalan = User::where('id',$transaksi->id_pangkalan)->where('stock_tabung','>=',0)->first();
                if($pangkalan){
                    $pangkalan->stock_tabung -= 1;
                    $pangkalan->update();
                }
                
    
                return response()->json([
                    'berhasil' => 'Berhasil Mengambil Tabung',
                ]);
            }else{
                return response()->json([
                    'error' => 'Stock anda Habis',
                ]);
            }
         
        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        $pelanggan = Pelanggan::where('id', $transaksi->id_pelanggan)->first();
        
     
            $pelanggan->jumlah_tabung += 1;
            $pelanggan->update();
        


        $pangkalan = User::where('id', $transaksi->id_pangkalan)->first();
        
        $pangkalan->stock_tabung += 1;
        $pangkalan->update();


        $transaksi->delete();

        return response()->json('data berhasil dihapus');
    }
}
