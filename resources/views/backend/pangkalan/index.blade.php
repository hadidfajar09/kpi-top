@extends('layouts.backend_master')

@section('title')
Daftar Pangkalan
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Pangkalan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Pangkalan</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('pangkalan.store') }}')"" ><i class="
                fa fa-plus-circle"></i> Tambah</button>
@endif
              

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
                        <table class="table table-bordered table-striped dataTable dtr-inline table-pangkalan">
                          <thead>
                            <th width="5%">No</th>
                            <th>Kode Pangkalan</th>
                            <th>Nama Pangkalan</th>
                            <th>Agent</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>Stock LPG</th>
                            <th>HET</th>
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

@include('backend.pangkalan.form')
@endsection


@push('scripts')
<script>
  let table;
        $(function(){
            table = $('.table-pangkalan').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('pangkalan.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'kode_user'},
                        {data: 'name'},
                        {data: 'nama_agent'},
                        {data: 'nama_kecamatan'},
                        {data: 'nama_desa'},
                        {data: 'stock_tabung'},
                        {data: 'harga_tabung'},
                        {data: 'aksi', searchable: false, sortable: false},
                ]
            });

            $('#modal-pangkalan').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-pangkalan form').attr('action'),
                        type: 'post',
                        data: $('#modal-pangkalan form').serialize()
                    })
                    .done((response) => {
                        $('#modal-pangkalan').modal('hide');
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
            $('#modal-pangkalan').modal('show');
            $('#modal-pangkalan .modal-title').text('Tambah Pangkalan');

            $('#modal-pangkalan form')[0].reset();
            $('#modal-pangkalan form').attr('action',url);
            $('#modal-pangkalan [name=_method]').val('post');
            $('#modal-pangkalan [name=name]').focus();

            $('#password, #password_confirmation').attr('required',true);
        }

        function editForm(url){
            $('#modal-pangkalan').modal('show');
            $('#modal-pangkalan .modal-title').text('Ubah Pangkalan');

            $('#modal-pangkalan form')[0].reset();
            $('#modal-pangkalan form').attr('action',url);
            $('#modal-pangkalan [name=_method]').val('put');
            $('#modal-pangkalan [name=password]').attr('required',false);;
            $('#modal-pangkalan [name=name]').focus();

            $('#password, #password_confirmation').attr('required',false);

            $.get(url)
              .done((response) => {
                $('#modal-pangkalan [name=name]').val(response.name);
                $('#modal-pangkalan [name=email]').val(response.email);
                $('#modal-pangkalan [name=alamat]').val(response.alamat);
                $('#modal-pangkalan [name=id_agent]').val(response.id_agent);
                $('#modal-pangkalan [name=id_kecamatan]').val(response.id_kecamatan);
                $('#modal-pangkalan [name=id_desa]').val(response.id_desa);
                $('#modal-pangkalan [name=stock_tabung]').val(response.stock_tabung);

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

<script type="text/javascript">
  $(document).ready(function() {
        $('select[name="id_kecamatan"]').on('change', function(){
            var id_kecamatan = $(this).val();
            if(id_kecamatan) {
                $.ajax({
                    url: "{{  url('/get/desa/') }}/"+id_kecamatan,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                       $("#id_desa").empty();
                             $.each(data,function(key,value){
                                 $("#id_desa").append('<option value="'+value.id+'">'+value.nama_desa+'</option>');
                             });


                    },
                   
                });
            } else {
                alert('danger');
            }
        });
    });
</script>
@endpush