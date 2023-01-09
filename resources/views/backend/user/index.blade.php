@extends('layouts.backend_master')

@section('title')
Daftar User
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar User</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User</li>
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
              <button class="btn btn-outline-danger btn-sm" onclick="addForm('{{ route('user.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>

            </div>
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">

                <!-- /.card -->

                <div class="card">
                
                  <!-- /.card-header -->
                  <div class="card-body">
                      <div class="row">
                        <div class="col-sm-12 table-responsive">
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-user"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Nama User</th>
                              <th>Email</th>
                              <th>Data</th>
                              <th>Akses</th>
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

@include('backend.user.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-user').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('user.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'data_karyawan'},
                        {data: 'level'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-user').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-user form').attr('action'),
                        type: 'post',
                        data: $('#modal-user form').serialize()
                    })
                    .done((response) => {
                        $('#modal-user').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Menyimpan Data')
                    })

                    .fail((errors) => {
                      Swal.fire('Data sudah ada atau terjadi kesalahan')
                        return;
                    });
                }
            });
          });

          function addForm(url){
            $('#modal-user').modal('show');
            $('#modal-user .modal-title').text('Tambah user');

            $('#modal-user form')[0].reset();
            $('#modal-user form').attr('action',url);
            $('#modal-user [name=_method]').val('post');
            $('#modal-user [name=name]').focus();

            $('#password, #password_confirmation').attr('required',true);
        }

        function editForm(url){
            $('#modal-user').modal('show');
            $('#modal-user .modal-title').text('Ubah user');

            $('#modal-user form')[0].reset();
            $('#modal-user form').attr('action',url);
            $('#modal-user [name=_method]').val('put');
            $('#modal-user [name=name]').focus();

            $('#password, #password_confirmation').attr('required',false);

            $.get(url)
              .done((response) => {
                $('#modal-user [name=email]').val(response.email);
                $('#modal-user [name=name]').val(response.name);
                $('#modal-user [name=karyawan_id]').val(response.karyawan_id);
                $('#modal-user [name=level]').val(response.level);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus user Ini?')){
            
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