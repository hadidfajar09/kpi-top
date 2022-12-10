@extends('layouts.backend_master')

@section('title')
Daftar Pengguna
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Pengguna</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Pengguna</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('pelanggan.store') }}')"" ><i class="
                fa fa-plus-circle"></i> Tambah</button>

                <button class="btn btn-outline-info btn-sm" onclick="cetakQrcode('{{ route('pelanggan.qrcode') }}')"" ><i class="
                  fa fa-id-card"></i> Cetak Kartu</button>

                  <button class="btn btn-outline-dark btn-sm" onclick="cetakJpg('{{ route('pelanggan.jpg') }}')"" ><i class="
                    fa fa-id-card"></i> Cetak JPG</button>

            

                  <button class="btn btn-outline-success btn-sm" onclick="importExcel('{{ route('pelanggan.import') }}')"" ><i class="
                    fa fa-id-card"></i> Import Excel</button>
              @endif

              @if (auth()->user()->level == 0)
              <button class="btn btn-outline-danger btn-sm" onclick="resetBulanan('{{ route('pelanggan.reset') }}')"" ><i class="
                fa fa-spinner"></i> Reset Bulanan</button>
              @endif
              
            </div>
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">

                <!-- /.card -->

                <div class="card">

                  <!-- /.card-header -->
                  <div class="card-body table-responsive">
                    <form action="" method="post" class="form-pelanggan">
                      @csrf
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-bordered table-striped dataTable dtr-inline table-pelanggan">
                          <thead>
                            <th style="width: 10%;">
                              <input type="checkbox" name="select_all" id="select_id">
                            </th>
                            
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>NIK</th> 
                            <th>Pekerjaan</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>Jumlah Tabung</th>
                            <th>Pangkalan</th>
                            <th width="10%"><i class="fa fa-cog"></i></th>
                          </thead>
                          
                        </table>
                      
                      </div>
                    </div>
                  </form>
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

@include('backend.pelanggan.form')
@include('backend.pelanggan.form_import')
@include('backend.pelanggan.form_reset')
@endsection


@push('scripts')
<script>
  let table;
        $(function(){
          $('body').addClass('sidebar-collapse')

            table = $('.table-pelanggan').DataTable({
              processing: true,
              autoWidth: false,
              aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
   
              
                ajax: {
                    url: '{{ route('pelanggan.data') }}'
                },
                columns: [
                        {data: 'select_all', searchable: false, sortable: false},
                        {data: 'kode_pelanggan'},
                        {data: 'nama_pelanggan'},
                        {data: 'nik'},
                        {data: 'nama_pekerjaan'},
                        {data: 'nama_kecamatan'},
                        {data: 'nama_desa'},
                        {data: 'jumlah_tabung'},
                        {data: 'name'},
                        {data: 'aksi', searchable: false, sortable: false},
                ],
                dom: 'Bfrltip',

                buttons: [
    'copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'
  ],


  
            });

            table.button(0).nodes().css('background', '#fd7e14');
            table.button(1).nodes().css('background', '#fd7e14');
            table.button(2).nodes().css('background', '#fd7e14');
            table.button(3).nodes().css('background', '#fd7e14');

            $('#modal-pelanggan').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-pelanggan form').attr('action'),
                        type: 'post',
                        data: $('#modal-pelanggan form').serialize()
                    })
                    .done((response) => {
                        $('#modal-pelanggan').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Menyimpan Data')
                    })

                    .fail((errors) => {
                      Swal.fire('Data sudah ada atau terjadi kesalahan')
                        return;
                    });
                }
            });

            $('#modal-import').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                      url: $('#modal-import form').attr('action'),
                        type: 'post',
                        data: $('#modal-import form').serialize(),
                        data: new FormData($('#modal-import form')[0]),
                        async: false,
                        processData: false,
                        contentType: false
                    })
                    .done((response) => {
                        $('#modal-import').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Menyimpan Data')
                    })

                    .fail((errors) => {
                      Swal.fire('Format Salah')
                        return;
                    });
                }
            });

            $('#modal-reset').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-reset form').attr('action'),
                        type: 'post',
                        data: $('#modal-reset form').serialize()
                    })
                    .done((response) => {
                        $('#modal-reset').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Berhasil Reset Jatah Bulanan')
                    })

                    .fail((errors) => {
                      Swal.fire('Data sudah ada atau terjadi kesalahan')
                        return;
                    });
                }
            });

            $('[name=select_all]').on('click', function(){
              $(':checkbox').prop('checked', this.checked);
            });

          });

          function addForm(url){
            $('#modal-pelanggan').modal('show');
            $('#modal-pelanggan .modal-title').text('Tambah Pengguna');

            $('#modal-pelanggan form')[0].reset();
            $('#modal-pelanggan form').attr('action',url);
            $('#modal-pelanggan [name=_method]').val('post');
            $('#modal-pelanggan [name=nama_pelanggan]').focus();

           
        }

        function importExcel(url){
            $('#modal-import').modal('show');
            $('#modal-import .modal-title').text('Masukkan File Excel');

            $('#modal-import form')[0].reset();
            $('#modal-import form').attr('action',url);
            $('#modal-import [name=_method]').val('post');
            $('#modal-import [name=file_excel]').focus();

           
        }

        function resetBulanan(url){
            $('#modal-reset').modal('show');
            $('#modal-reset .modal-title').text('Reset Bulanan');

            $('#modal-reset form')[0].reset();
            $('#modal-reset form').attr('action',url);
            $('#modal-reset [name=_method]').val('post');
            $('#modal-reset [name=file_excel]').focus();

           
        }

        function editForm(url){
            $('#modal-pelanggan').modal('show');
            $('#modal-pelanggan .modal-title').text('Ubah Pengguna');

            $('#modal-pelanggan form')[0].reset();
            $('#modal-pelanggan form').attr('action',url);
            $('#modal-pelanggan [name=_method]').val('put');
            $('#modal-pelanggan [name=password]').attr('required',false);;
            $('#modal-pelanggan [name=nama_pelanggan]').focus();

            

            $.get(url)
              .done((response) => {
                $('#modal-pelanggan [name=kode_pelanggan]').val(response.kode_pelanggan);
                $('#modal-pelanggan [name=nama_pelanggan]').val(response.nama_pelanggan);
                $('#modal-pelanggan [name=nik]').val(response.nik);
                $('#modal-pelanggan [name=no_kk]').val(response.no_kk);
                $('#modal-pelanggan [name=id_pekerjaan]').val(response.id_pekerjaan);
                $('#modal-pelanggan [name=id_kecamatan]').val(response.id_kecamatan);
                $('#modal-pelanggan [name=id_desa]').val(response.id_desa);
                $('#modal-pelanggan [name=alamat]').val(response.alamat);
                $('#modal-pelanggan [name=id_pangkalan]').val(response.id_pangkalan);
                $('#modal-pelanggan [name=jumlah_tabung]').val(response.jumlah_tabung);
                $('#modal-pelanggan [name=jatah_bulanan]').val(response.jatah_bulanan);
                $('#modal-pelanggan [name=id_penghasilan]').val(response.id_penghasilan);

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


        function cetakQrcode(url){
          if($('input:checked').length < 1){
            Swal.fire('Pilih Pengguna yang ingin dicetak kartunya')
            return;
          }
          else{
            $('.form-pelanggan').attr('action',url).attr('target','_blank').submit();
          }
        }

        function cetakJpg(url){
          if($('input:checked').length === 1){
            $('.form-pelanggan').attr('action',url).attr('target','_blank').submit();
            
          }
          else{
            Swal.fire('Pilih 1 Pengguna yang ingin dicetak kartunya')
            return;
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