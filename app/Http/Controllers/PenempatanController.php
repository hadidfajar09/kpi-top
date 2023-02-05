<?php

namespace App\Http\Controllers;

use App\Models\Penempatan;
use App\Models\Omset;
use App\Models\karyawan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenempatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.penempatan.index');
    }

    public function data()
    {
        $penempatan = Penempatan::orderBy('nama','asc')->get();

    if(auth()->user()->level == 0 || auth()->user()->level == 3){
    return datatables()
        ->of($penempatan)//source
        ->addIndexColumn() //untuk nomer
        ->addColumn('nama', function($penempatan){
            return '<h1 class="badge badge-light">'.$penempatan->nama.'</h1>';
        })
        ->addColumn('target', function($penempatan){
            return '<h1 class="badge badge-dark">'.'Rp. '.formatUang($penempatan->target).'</h1>';
        })
        ->addColumn('nominal', function($penempatan){
            $omset = Omset::whereMonth('tanggal_setor', Carbon::now()->month)->where('penempatan_id',$penempatan->id)->where('status',1)->sum('nominal');
            $persentase = $omset/$penempatan->target * 100;
            return '<h1 class="badge badge-success">'.round($persentase).'%'.'</h1>';
        })
        ->addColumn('aksi', function($penempatan){ //untuk aksi
            $button = '<div class="btn-group"><button type="button" onclick="editForm(`'.route('penempatan.update', $penempatan->id).'`)" class="btn btn-xs btn-info btn-flat"><i class="fas fa-edit"></i></button><button type="button" onclick="deleteData(`'.route('penempatan.destroy', $penempatan->id).'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button><a href="'.route('penempatan.laporan', $penempatan->id).'" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-eye"></i></a> </div>';
           return $button;
        })
        ->rawColumns(['aksi','nama','target','nominal'])//biar kebaca html
        ->make(true);

    }else{
    return datatables()
    ->of($penempatan)//source
    ->addIndexColumn() //untuk nomer
    ->addColumn('nama', function($penempatan){
        return '<h1 class="badge badge-light">'.$penempatan->nama.'</h1>';
    })
    ->addColumn('target', function($penempatan){
        return '<h1 class="badge badge-dark">'.formatUang($penempatan->target).'</h1>';
    })
    ->addColumn('aksi', function($penempatan){ //untuk aksi
        $button = '-';
       return $button;
    })
    ->rawColumns(['aksi','nama','target'])//biar kebaca html
    ->make(true);
}

    }

    public function detailOmset(Request $request, $id)
    {
        $penempatan = Penempatan::findOrFail($id);

        $tanggalAwal = date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if($request->tanggal_awal){
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }
        $start = Carbon::parse($tanggalAwal)->startOfDay();
        $end = Carbon::parse($tanggalAkhir)->endOfDay();

        $nominal_total = Omset::where('penempatan_id',$id)->whereBetween('tanggal_setor', [$start, $end])->where('status', 1)->sum('nominal');

        $persentase = $nominal_total/$penempatan->target * 100;

        return view('backend.penempatan.laporan.index', compact('tanggalAwal','tanggalAkhir','penempatan','nominal_total','persentase'));
    }

    // public function getData($awal, $akhir, $id)
    // {
    //     $penempatan = Penempatan::findOrFail($id);
    //    $no = 1;
    //    $data = array();
    //    $sisa_stock = 0;
    //    $total_stock = 0;

    //    while(strtotime($awal) <= strtotime($akhir)){
    //        $tanggal = $awal;

    //        $awal = date('Y-m-d', strtotime("+1 day",strtotime($awal)));

    //        $nominal = Omset::where('penempatan_id',$penempatan->id)->where('tanggal_setor', 'LIKE', "%$tanggal%")->where('status',1)->sum('nominal');

    //        $sales = karyawan::where('penempatan_id',$penempatan->id)->first();

    //        $nama_sales = $sales['name'] ?? 'Kosong';


    //        $sisa_stock = $nominal;
    //        $total_stock += $sisa_stock;

    //        $row = array();
    //        $row['DT_RowIndex'] = $no++;
    //        $row['tanggal'] = formatTanggal($tanggal);
    //        $row['outlet'] = $penempatan->nama;
    //        $row['sales'] = $nama_sales;
    //        $row['nominal'] = $sisa_stock;
    //        $row['catatan'] = 'dasdsa';
    //        $row['status'] = 'olala';

    //        $data[] = $row;

    //    }

    //    $data[] = [
    //        'DT_RowIndex' => '',
    //        'tanggal' => '',
    //        'outlet' => '',
    //        'sales' => '',
    //        'nominal' => '',
    //        'catatan' => 'Total Omset',
    //        'status' => $total_stock,
    //    ];
    //    return $data;
    // }

    public function dataReport($awal,$akhir,$id)
    {
        $start = Carbon::parse($awal)->startOfDay();
        $end = Carbon::parse($akhir)->endOfDay();
        $omset = Omset::where('penempatan_id',$id)->whereBetween('tanggal_setor', [$start, $end])->latest()->get();

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
           
    }

    public function exportDaily($awal, $akhir, $id)
    {
        $penempatan = Penempatan::findOrFail($id);
        $start = Carbon::parse($awal)->startOfDay();
        $end = Carbon::parse($akhir)->endOfDay();
        $omset = Omset::where('penempatan_id',$id)->whereBetween('tanggal_setor', [$start, $end])->where('status',1)->latest()->get();
        $nominal_total = Omset::where('penempatan_id',$id)->whereBetween('tanggal_setor', [$start, $end])->where('status', 1)->sum('nominal');
           $pdf = PDF::loadView('backend.penempatan.laporan.pdf', compact('awal','akhir','omset','penempatan','nominal_total'));
           $pdf->setPaper('a4','potrait');
           return $pdf->stream('Laporan Omset Outlet'.date('Y-m-d-his').' .pdf');
  
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
        $pemempatan = Penempatan::latest()->first() ?? new Penempatan();
        $pemempatan = new Penempatan();
        $pemempatan->nama = $request->nama;
        $pemempatan->target = $request->target;
        $pemempatan->alamat = $request->alamat;
        $pemempatan->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $penempatan = Penempatan::find($id);
        return response()->json($penempatan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penempatan $penempatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penempatan = Penempatan::find($id);
        $penempatan->nama = $request->nama;
        $penempatan->target = $request->target;
        $penempatan->alamat = $request->alamat;

        $penempatan->update();

        return response()->json('Penempatan Berhasil Disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penempatan  $penempatan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penempatan = Penempatan::find($id);
        $karyawan = karyawan::where('penempatan_id', $id)->get();

        
        foreach ($karyawan as $row) {
            $row->delete();
        }

        $penempatan->delete();

        return response()->json('data berhasil dihapus');
    }
}
