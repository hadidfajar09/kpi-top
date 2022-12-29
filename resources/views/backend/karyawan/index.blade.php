@extends('layouts.backend_master')

@section('title')
Daftar Karyawan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Karyawan</h1>
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
          <div class="card">
            <div class="card-header">
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
                              <th width="15%">Nama</th>
                              <th width="15%">Foto</th>
                              <th width="10%">Jabatan</th>
                              <th width="10%">Berkas</th>
                              <th width="15%">End Work</th>
                              <th width="5%">Status</th>
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

@include('backend.penempatan.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            $('body').addClass('sidebar-collapse')
            table = $('.table-agent').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('karyawan.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'name'},
                        {
                data: 'foto',
                name: 'foto',
                render: function(data, type, full, meta) {
                    return '<img src="'+data+'" width="120">';
                },
                searchable: false,
                orderable: false
            },
                        {data: 'jabatan'},
                        {data: 'berkas'},
                        {data: 'end_work'},
                        {data: 'status'},
                        {data: 'aksi', searchable: false, sortable: false},
                ],
                dom: 'Bfrltip',

buttons: [ 
'copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 
],
            });

             
            table.button(0).nodes().css('background', '#fd7e14');
            table.button(1).nodes().css('background', '#fd7e14');
            table.button(2).nodes().css('background', '#fd7e14');
            table.button(3).nodes().css('background', '#fd7e14');

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
            $('#modal-agent .modal-title').text('Tambah Penempatan');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('post');
            $('#modal-agent [name=nama]').focus();
        }

        function editForm(url){
            $('#modal-agent').modal('show');
            $('#modal-agent .modal-title').text('Ubah Penempatan');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('put');
            $('#modal-agent [name=nama]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-agent [name=nama]').val(response.nama);
                $('#modal-agent [name=alamat]').val(response.alamat);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Karyawan Ini?')){
            
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