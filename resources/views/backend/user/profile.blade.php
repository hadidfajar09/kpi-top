@extends('layouts.backend_master')

@section('title')
Edit Account
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
            <li class="breadcrumb-item active">Account</li>
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
              <h3 class="card-title">Edit Account</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('user.profile.update') }}" class="form-profile" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Nama Account</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $profile->name }}" required autofocus>
                    <span class="help-block with-errors"></span>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" name="email" id="email" disabled value="{{ $profile->email }}"> 
                                <span class="help-block with-errors"></span>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="profile_photo_path" class="col-md-2 control-label col-lg-offset-1">Foto Profile</label>
                  <div class="col-lg-6">

                      <input type="file" class="form-control" name="profile_photo_path" id="profile_photo_path" onchange="preview('.tampil-logo',this.files[0])">
                      <span class="help-block with-errors"></span><br>
                      <div class="tampil-logo">
                        <img src="{{ asset($profile->profile_photo_path ?? '') }}" width="200px">
                      </div>
                  </div>
              </div>

              <div class="form-group row">
                <label for="old_password" class="col-sm-2 col-form-label">Password Lama</label>
                <div class="col-sm-4">
                  <input type="password" class="form-control" name="old_password" id="old_password" >
                              <span class="help-block with-errors"></span>
                </div>
              </div>

              <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password Baru</label>
                <div class="col-sm-4">
                  <input type="password" class="form-control" name="password" id="password" >
                              <span class="help-block with-errors"></span>
                </div>
              </div>

              <div class="form-group row">
                <label for="password_confirmation" class="col-sm-2 col-form-label">Password Konfirmasi</label>
                <div class="col-sm-4">
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" rows="3" data-match="#password">
                              <span class="help-block with-errors text-danger"></span>
                </div>
              </div>
              
             
               
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                
                <button type="submit" class="btn btn-danger float-right">Update</button>
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
  
      $('#old_password').on('keyup', function(){
           if($(this).val() != "") $('#password').attr('required',true);
           else $('#password').attr('required',false);
       });  

      $('.form-profile').validator().on('submit',function(e){
          if(! e.preventDefault()){
              $.ajax({
                  url: $('.form-profile').attr('action'),
                  type: $('.form-profile').attr('method'),
                  data: new FormData($('.form-profile')[0]),
                  async: false,
                  processData: false,
                  contentType: false,
              })
              .done(response => {
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                                
                    $('.tampil-logo').html(`<img src="{{ url('/') }}/${response.profile_photo_path}" width="200px">`);
                   
                    $('.img-profil').attr('src', `{{ url('/') }}/${response.profile_photo_path}`);
                    
                  Swal.fire('Berhasil Update Data')
              })
              .fail(errors => {
                

                  if(errors.status == 422){
                    Swal.fire(errors.responseJSON)
                        
                    }else{
                      Swal.fire('Gagal Update Data')
                    }
                    return;
              });
          }
      });
  });

</script>
@endpush