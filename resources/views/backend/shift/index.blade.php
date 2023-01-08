@extends('layouts.backend_master')

@section('title')
Daftar Shift Karyawan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Shift Karyawan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Shift Karyawan</li>
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
              <button class="btn btn-outline-danger btn-sm" onclick="addForm('{{ route('shift.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button>
             

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-agent"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th width="15%">Shift</th>
                              <th width="10%">Masuk</th>
                              <th width="15%">Istirahat</th>
                              <th width="15%">Pulang</th>
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

@include('backend.shift.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-agent').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('shift.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'nama_shift'},
                        {data: 'masuk'},
                        {data: 'istirahat'},
                        {data: 'pulang'},
                        {data: 'aksi', searchable: false, sortable: false},
                ],
            });


            $('#modal-agent').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-agent form').attr('action'),
                        type: 'post',
                        data: $('#modal-agent form').serialize()
                    })
                    .done((response) => {
                        $('#modal-agent').modal('hide');
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
            $('#modal-agent').modal('show');
            $('#modal-agent .modal-title').text('Tambah Jadwal Shift');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('post');
            $('#modal-agent [name=nama_shift]').focus();
        }

        function editForm(url){
            $('#modal-agent').modal('show');
            $('#modal-agent .modal-title').text('Ubah Omset');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('put');
            $('#modal-agent [name=nama_shift]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-agent [name=nama_shift]').val(response.nama_shift);
                $('#modal-agent [name=masuk]').val(response.masuk);
                $('#modal-agent [name=istirahat]').val(response.istirahat);
                $('#modal-agent [name=pulang]').val(response.pulang);
              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Data Shift Ini?')){
            
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