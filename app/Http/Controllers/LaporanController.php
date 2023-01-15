<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if($request->tanggal_awal){
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        return view('backend.laporan.index', compact('tanggalAwal','tanggalAkhir'));
    }

    public function getData($awal, $akhir)
     {
        $no = 1;
        $data = array();
        $sisa_stock = 0;
        $total_stock = 0;

        while(strtotime($awal) <= strtotime($akhir)){
            $tanggal = $awal;

            $awal = date('Y-m-d', strtotime("+1 day",strtotime($awal)));

            $total_distribusi = Distribusi::where('status',1)->where('tanggal_pengantaran', 'LIKE', "%$tanggal%")->sum('drop_tabung');
            $transaksi_pelanggan = Transaksi::where('created_at', 'LIKE', "%$tanggal%")->sum('jumlah_tabung');

            $sisa_stock = $total_distribusi - $transaksi_pelanggan;
            $total_stock += $sisa_stock;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = formatTanggal($tanggal);
            $row['stock_pangkalan'] = $total_distribusi;
            $row['transaksi_pelanggan'] = $transaksi_pelanggan;
            $row['sisa_stock'] = $sisa_stock;

            $data[] = $row;

        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'stock_pangkalan' => '',
            'transaksi_pelanggan' => 'Total Stock Tabung',
            'sisa_stock' => $total_stock,
        ];
        return $data;
     }

     public function data($awal,$akhir)
     {
         
        $data = $this->getData($awal,$akhir);

        return datatables()
        ->of($data)
        ->make(true);
            
     }

    public function laporanExport()
    {
        # code...
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function show(Laporan $laporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function edit(Laporan $laporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laporan $laporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laporan $laporan)
    {
        //
    }
}
