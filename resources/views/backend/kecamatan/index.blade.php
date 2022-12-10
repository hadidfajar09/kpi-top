@extends('layouts.backend_master')

@section('title')
Daftar Kecamatan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Kecamatan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Kecamatan</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('kecamatan.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-kecamatan"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Nama Kecamatan</th>
                              <th>HET LPG</th>
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

@include('backend.kecamatan.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-kecamatan').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('kecamatan.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'nama_kecamatan'},
                        {data: 'harga_tabung'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-kecamatan').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-kecamatan form').attr('action'),
                        type: 'post',
                        data: $('#modal-kecamatan form').serialize()
                    })
                    .done((response) => {
                        $('#modal-kecamatan').modal('hide');
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
            $('#modal-kecamatan').modal('show');
            $('#modal-kecamatan .modal-title').text('Tambah Kecamatan');

            $('#modal-kecamatan form')[0].reset();
            $('#modal-kecamatan form').attr('action',url);
            $('#modal-kecamatan [name=_method]').val('post');
            $('#modal-kecamatan [name=nama_kecamatan]').focus();
        }

        function editForm(url){
            $('#modal-kecamatan').modal('show');
            $('#modal-kecamatan .modal-title').text('Ubah Kecamatan');

            $('#modal-kecamatan form')[0].reset();
            $('#modal-kecamatan form').attr('action',url);
            $('#modal-kecamatan [name=_method]').val('put');
            $('#modal-kecamatan [name=nama_kecamatan]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-kecamatan [name=nama_kecamatan]').val(response.nama_kecamatan);
                $('#modal-kecamatan [name=harga_tabung]').val(response.harga_tabung);
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