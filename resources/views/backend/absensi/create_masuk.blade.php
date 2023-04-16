@extends('layouts.backend_master')

@section('title')
Absen Masuk
@endsection

@push('css')

<style>
  @media (max-width: 576px) {
    #my_camera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }

    #results img {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        border-radius: 15px;
    }

    
}

#map { height: 380px; }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

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
            <li class="breadcrumb-item active">Absen Masuk</li>
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
              <h3 class="card-title">Absen Masuk</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('absen.login') }}" class="form-profile" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body ">
                <div class="card card-danger col-lg-2 mb-3" style="margin: auto;">
                  <div class="card-header">
                  <h1 class="card-title text-center"  id="clock"></h1>
                  </div>
                  
                  </div>
    
                      <div id="map"></div>
                   
                <div class="form-group row">
                  <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Camera</label>
                  <input type="hidden" id="latitude" name="latitude">
                  <input type="hidden" id="longitude" name="longitude">
                  
                  <div id="my_camera"></div>
                  <div class="col-md-8">
                      <input type=button class="btn btn-outline-danger btn-sm" style="display: block;" value="Preview" onClick="take_snapshot()">
                      <input type="hidden" name="foto_masuk" class="image-tag">
                  </div>
                </div>
                <h1 class="text-danger" style="font-weight: bold; font-size: 60px; text-align: center;" id="timer"></h1>

                  <div class="form-group row">
                    <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">HASIL</label>
                                <div class="col-md-8">
                        <div id="results"></div>
                    </div>
                    </div>
    
                    {{-- <div class="form-group row">
                      <label for="deskripsi" class="col-md-4 col-md-offset-1 control-label">Keterangan</label>
                      <div class="col-md-8">
                          <textarea class="form-control" name="keterangan" id="" cols="30" rows="5"></textarea>
                          <span class="help-block with-errors text-danger"></span>
          
                      </div>
                  </div> --}}

               
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

<script language="JavaScript">

  var latitude = document.getElementById('latitude');
  var longitude = document.getElementById('longitude');

  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
  }

  function successCallback(position){
    latitude.value = position.coords.latitude;
    longitude.value = position.coords.longitude;
    var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">Zeronine</a>'
}).addTo(map);
    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

    //titik kordinat kantor
    var circle = L.circle([{{ json_encode($latitude) }}, {{ json_encode($longitude) }}], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 10
}).addTo(map);
    

  }



  function errorCallback(){

  }
         
    Webcam.set({
        width: 350,
        height: 500,
        align:'center',
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    
    Webcam.attach( '#my_camera' );
    
    function take_snapshot() {
      var timer = document.getElementById("timer");
var countDown = 5;

// memulai hitungan mundur
timer.innerHTML = countDown;
var intervalId = setInterval(function(){
  countDown--;
  timer.innerHTML = countDown;
  if (countDown <= 0) {
    clearInterval(intervalId);
    timer.innerHTML = "Berhasil";
  }
}, 1000); // setiap 1 detik (1000 ms)
      setTimeout(function(){
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" width="450"/>';
        } );
  }, 5000); // 1000 ms = 1 detik
        
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