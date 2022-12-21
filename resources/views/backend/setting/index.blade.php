@extends('layouts.backend_master')

@section('title')
Pengaturan Aplikasi
@endsection

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
            <li class="breadcrumb-item active">Pengaturan</li>
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
          <div class="card card-orange">
            <div class="card-header">
              <h3 class="card-title">Pengaturan Aplikasi</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('setting.update') }}" class="form-horizontal form-setting" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label for="nama_perusahaan" class="col-sm-2 col-form-label">Nama Perusahaan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="nama_perusahaan" id="nama_perusahaan" required autofocus>
                    <span class="help-block with-errors"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                  <div class="col-sm-4">
                    <textarea class="form-control" name="alamat" id="alamat" rows="3" required> </textarea>
                                <span class="help-block with-errors"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="nomor_handphone" class="col-sm-2 col-form-label">Nomor Handphone</label>
                  <div class="col-sm-4">
                    <input type="number" class="form-control" name="nomor_handphone" id="nomor_handphone" required>
                    <span class="help-block with-errors"></span>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="" class="col-md-2 control-label col-lg-offset-1">Logo Perusahaan</label>
                  <div class="col-lg-6">

                      <input type="file" class="form-control" name="path_logo" id="path_logo" onchange="preview('.tampil-logo',this.files[0])">
                      <span class="help-block with-errors"></span><br>
                      <div class="tampil-logo"></div>
                  </div>
              </div>

              
          
             
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                
                <button type="submit" class="btn btn-warning float-right">Update</button>
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
   
<script>
  $(function(){
      showData();
      $('.form-setting').validator().on('submit',function(e){
          if(! e.preventDefault()){
              $.ajax({
                  url: $('.form-setting').attr('action'),
                  type: $('.form-setting').attr('method'),
                  data: new FormData($('.form-setting')[0]),
                  async: false,
                  processData: false,
                  contentType: false,
              })
              .done(response => {
                  showData();
                  Swal.fire('Berhasil Update Data')
              })
              .fail(errorrs => {
                Swal.fire('Gagal Update Data')
                  return;
              });
          }
      });
  });

  function showData(){
      $.get('{{ route('setting.show') }}')
      .done(response => {
          $('#nama_perusahaan').val(response.nama_perusahaan);
          $('#nomor_handphone').val(response.nomor_handphone);
          $('#alamat').val(response.alamat);
          $('title').text(response.nama_perusahaan + ' | Pengaturan');

          $('.tampil-logo').html(`<img src="{{ url('/') }}/${response.path_logo}" width="200px">`);
          $('.tampil-pelanggan').html(`<img src="{{ url('/') }}/${response.path_pelanggan}" width="300px">`);
          $('.tampil-pelanggan-bg').html(`<img src="{{ url('/') }}/${response.path_pelanggan_bg}" width="300px">`);
          $('[rel=icon]').attr('href',`{{ url('/') }}/${response.path_logo}`);
      })
      .fail(errors => {
        Swal.fire('Gagal Menampilan Data')
          return;
      })
  }
</script>
@endpush