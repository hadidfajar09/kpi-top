@extends('layouts.backend_master')

@section('title')
Daftar Pekerjaan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Pekerjaan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Pekerjaan</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('pekerjaan.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-pekerjaan"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Nama Pekerjaan</th>
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

@include('backend.pekerjaan.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-pekerjaan').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('pekerjaan.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'nama_pekerjaan'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-pekerjaan').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-pekerjaan form').attr('action'),
                        type: 'post',
                        data: $('#modal-pekerjaan form').serialize()
                    })
                    .done((response) => {
                        $('#modal-pekerjaan').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Menyimpan Data')
                    })

                    .fail((errors) => {
                      
                        return;
                    });
                }
            });
          });

          function addForm(url){
            $('#modal-pekerjaan').modal('show');
            $('#modal-pekerjaan .modal-title').text('Tambah Pekerjaan');

            $('#modal-pekerjaan form')[0].reset();
            $('#modal-pekerjaan form').attr('action',url);
            $('#modal-pekerjaan [name=_method]').val('post');
            $('#modal-pekerjaan [name=nama_pekerjaan]').focus();
        }

        function editForm(url){
            $('#modal-pekerjaan').modal('show');
            $('#modal-pekerjaan .modal-title').text('Ubah Pekerjaan');

            $('#modal-pekerjaan form')[0].reset();
            $('#modal-pekerjaan form').attr('action',url);
            $('#modal-pekerjaan [name=_method]').val('put');
            $('#modal-pekerjaan [name=nama_pekerjaan]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-pekerjaan [name=nama_pekerjaan]').val(response.nama_pekerjaan);

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