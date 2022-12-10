@extends('layouts.backend_master')

@section('title')
Daftar Desa/Kelurahan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Desa/Kelurahan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Desa/Kelurahan</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('desa.store') }}')"" ><i class="
                fa fa-plus-circle"></i> Tambah</button>

            </div>
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">

                <!-- /.card -->

                <div class="card">

                  <!-- /.card-header -->
                  <div class="card-body table-responsive">
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-bordered table-striped dataTable dtr-inline table-desa">
                          <thead>
                            <th width="5%">No</th>
                            <th>Nama Kecamatan</th>
                            <th>Nama Desa/Kelurahan</th>
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

@include('backend.desa.form')
@endsection


@push('scripts')
<script>
  let table;
        $(function(){
            table = $('.table-desa').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('desa.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'nama_kecamatan'},
                        {data: 'nama_desa'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-desa').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-desa form').attr('action'),
                        type: 'post',
                        data: $('#modal-desa form').serialize()
                    })
                    .done((response) => {
                        $('#modal-desa').modal('hide');
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
            $('#modal-desa').modal('show');
            $('#modal-desa .modal-title').text('Tambah Desa');

            $('#modal-desa form')[0].reset();
            $('#modal-desa form').attr('action',url);
            $('#modal-desa [name=_method]').val('post');
            $('#modal-desa [name=nama_desa]').focus();
        }

        function editForm(url){
            $('#modal-desa').modal('show');
            $('#modal-desa .modal-title').text('Ubah Desa');

            $('#modal-desa form')[0].reset();
            $('#modal-desa form').attr('action',url);
            $('#modal-desa [name=_method]').val('put');
            $('#modal-desa [name=nama_desa]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-desa [name=id_kecamatan]').val(response.id_kecamatan);
                $('#modal-desa [name=nama_desa]').val(response.nama_desa);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        

        function deleteData(url) {

          if(confirm('Yakin Ingin Hapus Pekerjaan Ini?')){
           
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