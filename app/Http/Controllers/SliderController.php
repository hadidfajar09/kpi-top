<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.slider.index');
    }

    public function data()
    {
        $slider = Slider::orderBy('id','desc')->get();

        return datatables()
            ->of($slider)//source
            ->addIndexColumn() //untuk nomer
            ->addColumn('path_slider', function($slider){
                return '<img src="'.$slider->path_slider.' " width="100">';
            })
            ->addColumn('level', function($slider){
                if($slider->level == 1){
                    return '<span class="badge badge-danger">Agent</span>';
  
                }else{
                  return '<span class="badge badge-success">Pangkalan</span>';
                }
            })
            ->addColumn('aksi', function($slider){ //untuk aksi
                $button = '<div class="btn-group"> <button onclick="editForm(`'.route('slider.update', $slider->id).'`)" class="btn bg-gradient-info btn-xs"><i class="fas fa-edit"></i></button><button onclick="deleteData(`'.route('slider.destroy', $slider->id).'`)" class="btn bg-gradient-danger btn-xs delete-slider" ><i class="fas fa-trash"></i></button> </div>';

               return $button;
               
            })
            ->rawColumns(['aksi','level','path_slider'])//biar kebaca
            ->make(true);
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
       
        $slider = new Slider();

        $image = $request->file('path_slider');

        $nama = 'slider-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(1200,600)->save('img/slider/'.$nama);

        $path_slider = 'img/slider/'.$nama;
        // $image->move(public_path('/img/slider/'),$nama);

        $slider->nama_slider = $request->nama_slider;
        $slider->level = $request->level;
        $slider->path_slider = $path_slider;
        $slider->save();
      

       return response()->json('Data berhasil disimpan',200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider = Slider::find($id);
        return response()->json($slider);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);

        if($request->hasFile('path_slider')){
            $image = $request->file('path_slider');
       
            $nama = 'slider-'.date('Y-m-dHis').'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(1200,600)->save('img/slider/'.$nama);
    
            $path_slider = 'img/slider/'.$nama;

            $slider->path_slider = $path_slider;
        }
       
        // $image->move(public_path('/img/slider/'),$nama);
        $slider->nama_slider = $request->nama_slider;
        $slider->level = $request->level;
        
        $slider->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);
        Storage::disk('public')->delete($slider->path_slider);
        $slider->delete();

        return response()->json('data berhasil dihapus');
    }
}
