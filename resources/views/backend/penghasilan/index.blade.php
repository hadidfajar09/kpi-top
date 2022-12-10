@extends('layouts.backend_master')

@section('title')
Daftar Penghasilan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Penghasilan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Penghasilan</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('penghasilan.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-penghasilan"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Nominal Gaji/Bulan</th>
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

@include('backend.penghasilan.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-penghasilan').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('penghasilan.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'nominal_gaji'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-penghasilan').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-penghasilan form').attr('action'),
                        type: 'post',
                        data: $('#modal-penghasilan form').serialize()
                    })
                    .done((response) => {
                        $('#modal-penghasilan').modal('hide');
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
            $('#modal-penghasilan').modal('show');
            $('#modal-penghasilan .modal-title').text('Tambah Nominal Gaji');

            $('#modal-penghasilan form')[0].reset();
            $('#modal-penghasilan form').attr('action',url);
            $('#modal-penghasilan [name=_method]').val('post');
            $('#modal-penghasilan [name=nominal_gaji]').focus();
        }

        function editForm(url){
            $('#modal-penghasilan').modal('show');
            $('#modal-penghasilan .modal-title').text('Ubah Nominal Range Gaji');

            $('#modal-penghasilan form')[0].reset();
            $('#modal-penghasilan form').attr('action',url);
            $('#modal-penghasilan [name=_method]').val('put');
            $('#modal-penghasilan [name=nominal_gaji]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-penghasilan [name=nominal_gaji]').val(response.nominal_gaji);

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