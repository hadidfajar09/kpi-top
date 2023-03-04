@extends('layouts.backend_master')

@section('title')
Show Daily COD
@endsection

@push('css')

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
            <li class="breadcrumb-item active">Daily COD</li>
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
              <h3 class="card-title">Data COD</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
              <div class="card-body">
               

                <div class="form-group row">

                  <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Foto Kebersihan</label>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                      <a href="{{ asset($cod->path_foto) }}" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                      <img src="{{ asset($cod->path_foto) }}" class="img-fluid" width="550" alt="">
                    </a>
                  </div>
                  <div class="col-md-3">
                    <a href="{{ asset($cod->path_foto_2) }}" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                    <img src="{{ asset($cod->path_foto_2) }}" class="img-fluid" width="550" alt="">
                  </a>
                </div>
                <div class="col-md-3">
                  <a href="{{ asset($cod->path_foto_3) }}" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                  <img src="{{ asset($cod->path_foto_3) }}" class="img-fluid" width="550" alt="">
                </a>
              </div>
              <div class="col-md-3">
                <a href="{{ asset($cod->path_foto_4) }}" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="{{ asset($cod->path_foto_4) }}" class="img-fluid" width="550" alt="">
              </a>
            </div>
                  </div>
    
                    <div class="form-group row">
                      <label for="deskripsi" class="col-md-4 col-md-offset-1 control-label">Catatan</label>
                      <div class="col-md-8">
                          <textarea class="form-control" name="catatan" id="" cols="30" rows="5" disabled>{{ $cod->catatan }}</textarea>
                          <span class="help-block with-errors text-danger"></span>
          
                      </div>
                  </div>


            

               
              </div>
              <!-- /.card-body -->
             
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

@endpush