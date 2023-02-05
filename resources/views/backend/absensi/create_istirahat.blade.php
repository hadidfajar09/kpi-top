@extends('layouts.backend_master')

@section('title')
Absen Istirahat
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
            <li class="breadcrumb-item active">Absen Istirahat</li>
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
              <h3 class="card-title">Absen Istirahat</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('absen.rest') }}" class="form-profile" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body ">
                <div class="card card-danger col-lg-2 mb-3" style="margin: auto;">
                  <div class="card-header">
                  <h1 class="card-title text-center"  id="clock"></h1>
                  </div>
                  
                  </div>
    
                <div class="form-group row">
                  <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Camera</label>
                  <div id="my_camera" width="50"></div>
                  <div class="col-md-8">
                      <input type=button class="btn btn-outline-danger btn-sm" style="display: block;" value="Preview" onClick="take_snapshot()">
                      <input type="hidden" name="foto_istirahat" class="image-tag">
                  </div>
                  </div>

                  <div class="form-group row">
                    <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">HASIL</label>
                                <div class="col-md-8">
                        <div id="results"></div>
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

        
Webcam.set({
        width: 350,
        height: 500,
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

    function displayTime() {
  var currentTime = new Date();
  var hours = currentTime.getHours();
  var minutes = currentTime.getMinutes();
  var seconds = currentTime.getSeconds();

  // tambahkan 0 di depan angka jika kurang dari 10
  minutes = (minutes < 10 ? "0" : "") + minutes;
  seconds = (seconds < 10 ? "0" : "") + seconds;

  // pilih AM atau PM sesuai dengan jam
  var ampm = (hours < 12) ? "AM" : "PM";
  hours = (hours > 12) ? hours - 12 : hours;
  hours = (hours == 0) ? 12 : hours;

  // tampilkan jam pada elemen HTML
  var clockDiv = document.getElementById('clock');
  clockDiv.innerHTML = "Pukul " + hours + ":" + minutes + ":" + seconds + " " + ampm;
}

setInterval(displayTime, 1000);
</script>
@endpush