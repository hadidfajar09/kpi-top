@extends('layouts.backend_master')

@section('title')
Edit Daily Grooming
@endsection

@push('css')
<style>
  @media (max-width: 576px) {
    #my_camera video {
        max-width: 80%;
        max-height: 80%;
    }

    #results img {
        max-width: 80%;
        max-height: 80%;

    }
}
</style>
@endpush

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Daily Grooming</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">Data karyawan</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('grooming.updated',$grooming->id) }}" class="form-profile" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Foto lama</label>
                    <div class="col-md-8">
                        <img src="{{ asset($grooming->path_foto) }}" width="450" alt="">
                  </div>
                  </div>
    
                <div class="form-group row">
                  <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Foto</label>
                  <div id="my_camera" width="50"></div>
                  <div class="col-md-8">
                    <input type=button class="btn btn-outline-danger btn-sm" style="display: block;" value="Preview" onClick="take_snapshot()">
                      <input type="hidden" name="path_foto" class="image-tag">
                  </div>
                  </div>

                  <div class="form-group row">
                    <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">HASIL</label>
                                <div class="col-md-8">
                        <div id="results"></div>
                    </div>
                    </div>
    
                    <div class="form-group row">
                      <label for="deskripsi" class="col-md-4 col-md-offset-1 control-label">Catatan</label>
                      <div class="col-md-8">
                          <textarea class="form-control" name="catatan" id="" cols="30" rows="5">{{ $grooming->catatan }}</textarea>
                          <span class="help-block with-errors text-danger"></span>
          
                      </div>
                  </div>


            

               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                
                <button type="submit" class="btn btn-danger float-right">Tambahkan</button>
              </div>
              <!-- /.card-footer -->
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>


@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script language="JavaScript">

    Webcam.set('constraints',{
            facingMode: "environment"
        });
        
    Webcam.set({
        width: 490,
        height: 350,
        align:'center',
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    
    Webcam.attach( '#my_camera' );
    
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width="450"/>';
        } );
    }
</script>
@endpush