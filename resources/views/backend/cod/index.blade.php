@extends('layouts.backend_master')

@section('title')
Daftar COD
@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar COD</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">COD</li>
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
              <a class="btn btn-outline-danger btn-sm" href="{{ route('cod.create') }}"><i class="fa fa-plus-circle"></i> Tambah</a>

              @if (auth()->user()->level == 0)
              <button class="btn btn-success btn-sm" onclick="acceptSelected('{{ route('cod.accselected') }}')"> <i class="fa fa-check"></i>   Terima COD</button>
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
                          <form action="" method="post" class="form-cod">
                            @csrf
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-agent"
                            >
                            <thead>
                              <th width="5%">
                                <input type="checkbox" name="select_all" id="select_id">
                            </th>
                              <th width="5%">No</th>
                              <th width="15%">Tanggal</th>
                              <th width="10%">Karyawan</th>
                              <th width="10%">Foto</th>
                              <th>Catatan</th>
                              <th width="5%">Status</th>
                              <th width="10%"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </form>
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

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script>

      let table;
        $(function(){
            table = $('.table-agent').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('cod.data') }}'
                },
                columns: [
                  {data: 'select_all', searchable: false, sortable: false},
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'tanggal'},
                        {data: 'user'},
                        {data: 'path_foto'},
                        {data: 'catatan'},
                        {data: 'status'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
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
            $('#modal-agent .modal-title').text('Tambah Jabatan');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('post');
            $('#modal-agent [name=jabatan]').focus();
        }

        function editForm(url){
            $('#modal-agent').modal('show');
            $('#modal-agent .modal-title').text('Ubah Jabatan');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('put');
            $('#modal-agent [name=jabatan]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-agent [name=jabatan]').val(response.jabatan);
                $('#modal-agent [name=deskripsi]').val(response.deskripsi);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus COD Ini?')){
            
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

        function acceptSelected(url){
          if ($('input:checked').length > 1) {
            if(confirm('Yakin ingin terima COD terpilih?')){
              $.post(url, $('.form-cod').serialize())
              .done((response) => {
                  table.ajax.reload();
              })
  
              .fail((response) => {
                alert('tidak dapat terima COD');
                return;
              });

            }
          } else {
            alert('Pilih data yang ingin diterima');
            return;
          }
          
        }
</script>

@endpush