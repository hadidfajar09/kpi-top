@extends('layouts.backend_master')

@section('title')
Tambah Karyawan
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" >
@endpush

@section('content')
<style type="text/css">
  .bootstrap-tagsinput .tag{
      margin-right: 2px;
      padding: 3px;
      background-color: rgba(88, 202, 139, 0.637);
      color: #000000;
      font-weight: 700px;
      border-radius: 10px;
  } 
</style>
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
            <li class="breadcrumb-item active">Karyawan</li>
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
            <form action="{{ route('karyawan.store') }}" class="form-profile" data-toggle="validator" method="post" enctype="multipart/form-data" >
              @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Nama Karyawan</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" id="name"  required autofocus>
                    <span class="help-block with-errors text-danger"></span>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" class="form-control" name="email" id="email"  required autofocus>
                    <span class="help-block with-errors text-danger"></span>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-4">
                    <input type="password" class="form-control" name="password" id="password"  required autofocus>
                    <span class="help-block with-errors text-danger"></span>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Jabatan</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="jabatan_id" id="jabatan_id" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatan as $key => $row)
                                <option value="{{ $key }}">{{ $row }}</option>
                            @endforeach
              
                          </select>
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Kontrak</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="kontrak_id" id="kontrak_id" required>
                            <option value="">Pilih Kontrak</option>
                            @foreach ($kontrak as $key => $row)
                                <option value="{{ $key }}">{{ $row }}</option>
                            @endforeach
              
                          </select>
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Penempatan</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="penempatan_id" id="penempatan_id" required>
                            <option value="">Pilih Lokasi Penempatan</option>
                            @foreach ($penempatan as $key => $row)
                                <option value="{{ $key }}">{{ $row }}</option>
                            @endforeach
              
                          </select>
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Jadwal Shift</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="shift_id" id="shift_id" required>
                            <option value="">Pilih Shift</option>
                            @foreach ($shift as $key => $row)
                                <option value="{{ $key }}">{{ $row }}</option>
                            @endforeach
              
                          </select>
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Berkas</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" value="KTP,IJAZAH,AKTE" name="berkas" data-role="tagsinput">
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Upload Berkas</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="file" name="path_berkas">
                      <span class="text-danger">"Gabungkan menjadi 1 file pdf"</span>
                                  <span class="help-block with-errors text-danger"></span>

                                  @error('path_berkas')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3" required></textarea>
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Nomer HP</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="number" name="nomor">
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Join Date</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="join_date">
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">End Date</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="end_work">
                                  <span class="help-block with-errors text-danger"></span>
                    </div>
                  </div>



                <div class="form-group row">
                  <label for="foto" class="col-md-2 control-label col-lg-offset-1">Pas Poto</label>
                  <div class="col-lg-6">

                      <input type="file" class="form-control" name="foto" id="foto" onchange="preview('.tampil-logo',this.files[0])">
                      @error('foto')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                      <span class="help-block with-errors"></span><br>
                      <div class="tampil-logo">
                        <img src="" width="200px">
                      </div>
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
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js" ></script>

@endpush