@extends('layouts.backend_master')

@section('title')
Daftar Pengumuman
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Pengumuman</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Pengumuman</li>
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
          <div class="card">
            <div class="card-header">
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('slider.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>

            </div>
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">

                <!-- /.card -->

                <div class="card">
                
                  <!-- /.card-header -->
                  <div class="card-body">
                      <div class="row">
                        <div class="col-sm-12">
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-slider"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Banner</th>
                              <th>Keterangan</th>
                              <th>Tampil</th>
                              <th width="10%"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>

            <!-- /.card-footer -->
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

@include('backend.slider.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-slider').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('slider.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'path_slider'},
                        {data: 'nama_slider'},
                        {data: 'level'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-slider').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-slider form').attr('action'),
                        type: 'post',
                        data: $('#modal-slider form').serialize(),
                        data: new FormData($('#modal-slider form')[0]),
                        async: false,
                        processData: false,
                        contentType: false
                    })
                    .done((response) => {
                        $('#modal-slider').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Menyimpan Data')
                    })

                    .fail((errors) => {
                      Swal.fire('Data sudah ada')
                        return;
                    });
                }
            });
          });

          function addForm(url){
            $('#modal-slider').modal('show');
            $('#modal-slider .modal-title').text('Tambah Pengumuman');

            $('#modal-slider form')[0].reset();
            $('#modal-slider form').attr('action',url);
            $('#modal-slider [name=_method]').val('post');
            $('#modal-slider [name=nama_slider]').focus();

        }

        function editForm(url){
            $('#modal-slider').modal('show');
            $('#modal-slider .modal-title').text('Ubah Pengumuman');

            $('#modal-slider form')[0].reset();
            $('#modal-slider form').attr('action',url);
            $('#modal-slider [name=_method]').val('put');
            $('#modal-slider [name=nama_slider]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-slider [name=nama_slider]').val(response.nama_slider);
                $('#modal-slider [name=level]').val(response.level);
                $('.tampil-slider').html(`<img src="{{ url('/') }}/${response.path_slider}" width="200">`);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Agent Ini?')){
            
          $.post(url, {
            '_token': $('[name=csrf-token]').attr('content'),
            '_method': 'delete'
          })
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil Menghapus Data')
                })
                .fail((errors) => {
                  Swal.fire('Gagal Hapus Data')
                    return;
              });
          }
        }
    </script>
@endpush