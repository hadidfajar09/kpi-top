@extends('layouts.backend_master')

@section('title')
Daftar Kontrak Kerja
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Masa Kontrak Kerja</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Kontrak</li>
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
              @if (auth()->user()->level == 0 || auth()->user()->level == 3)
              <button class="btn btn-outline-danger btn-sm" onclick="addForm('{{ route('kontrak.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>
              @endif
             

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-kontrak"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th width="15%">Masa Kontrak</th>
                              <th>Deskripsi</th>
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

@include('backend.kontrak.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-kontrak').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('kontrak.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'kontrak'},
                        {data: 'deskripsi'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-kontrak').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-kontrak form').attr('action'),
                        type: 'post',
                        data: $('#modal-kontrak form').serialize()
                    })
                    .done((response) => {
                        $('#modal-kontrak').modal('hide');
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
            $('#modal-kontrak').modal('show');
            $('#modal-kontrak .modal-title').text('Tambah Masa Kontrak');

            $('#modal-kontrak form')[0].reset();
            $('#modal-kontrak form').attr('action',url);
            $('#modal-kontrak [name=_method]').val('post');
            $('#modal-kontrak [name=kontrak]').focus();
        }

        function editForm(url){
            $('#modal-kontrak').modal('show');
            $('#modal-kontrak .modal-title').text('Ubah Kontrak');

            $('#modal-kontrak form')[0].reset();
            $('#modal-kontrak form').attr('action',url);
            $('#modal-kontrak [name=_method]').val('put');
            $('#modal-kontrak [name=kontrak]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-kontrak [name=kontrak]').val(response.kontrak);
                $('#modal-kontrak [name=deskripsi]').val(response.deskripsi);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Masa Kontrak Ini?')){
            
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