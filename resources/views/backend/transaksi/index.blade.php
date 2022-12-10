@extends('layouts.backend_master')

@section('title')
Daftar Transaksi
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Transaksi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
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
              {{-- <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('transaksi.store') }}')"" ><i class="fa fa-plus-circle"></i> Tambah</button> --}}
              {{-- <a class="btn btn-outline-primary btn-sm" href="{{ route('transaksi.export') }}" ><i class="fa fa-plus-circle"></i> Export</a> --}}

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-transaksi"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th>Kode</th>
                              <th>Pengguna</th>
                              <th>Pangkalan</th>
                              <th>Tanggal Transaksi</th>
                              <th>Jumlah Tabung</th>
                              <th>HET</th>
                              <th width="10%"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>

                  {{-- <div class="card-body">
                    <center>
                      <div id="reader" style="width: 400px;">
                        
                    </center>
                    <div class="col-md-12">
                      <input class="form-control" type="text" id="result">
                    </div>
                  </div> --}}
                  
                 

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

@include('backend.transaksi.form')
@endsection


@push('scripts')
    <script>

      let table;
        $(function(){
            table = $('.table-transaksi').DataTable({
              processing: true,
              autoWidth: false,
              aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
                ajax: {
                    url: '{{ route('transaksi.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'kode_transaksi'},
                        {data: 'nama_pelanggan'},
                        {data: 'nama_pangkalan'},
                        {data: 'tanggal_ambil'},
                        {data: 'jumlah_tabung'},
                        {data: 'harga_tabung'},
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


            $('#modal-transaksi').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-transaksi form').attr('action'),
                        type: 'post',
                        data: $('#modal-transaksi form').serialize()
                    })
                    .done((response) => {
                        $('#modal-transaksi').modal('hide');
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
            $('#modal-transaksi').modal('show');
            $('#modal-transaksi .modal-title').text('Tambah Transaksi');

            $('#modal-transaksi form')[0].reset();
            $('#modal-transaksi form').attr('action',url);
            $('#modal-transaksi [name=_method]').val('post');
            $('#modal-transaksi [name=name]').focus();

        }

        function editForm(url){
            $('#modal-transaksi').modal('show');
            $('#modal-transaksi .modal-title').text('Ubah Transaksi');

            $('#modal-transaksi form')[0].reset();
            $('#modal-transaksi form').attr('action',url);
            $('#modal-transaksi [name=_method]').val('put');
            $('#modal-transaksi [name=name]').focus();


            $.get(url)
              .done((response) => {
                $('#modal-transaksi [name=email]').val(response.email);
                $('#modal-transaksi [name=name]').val(response.name);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Agent Ini?')){
            
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

{{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"> </script> --}}
<script>
  function onScanSuccess(decodedText, decodedResult) {
  // handle the scanned code as you like, for example:
  // console.log(`Code matched = ${decodedText}`, decodedResult);

  $('#result').val(decodedText);
  html5QrcodeScanner.clear();

  let id = decodedText;


                    $.ajax({
                        url: "{{ route('transaksi.store') }}",
                        type: 'post',
                        data: {
                          '_token': $('[name=csrf-token]').attr('content'),
                          'qr_code' : id
                        }
                    })
                .done((response) => {
                    table.ajax.reload();
                    Swal.fire('Berhasil Scan')
                    console.log(response);
                })
                .fail((errors) => {
                  Swal.fire('QR Tidak terdaftar')
                    return;
              });
}

function onScanFailure(error) {
  // handle scan failure, usually better to ignore and keep scanning.
  // for example:
  console.warn(`Code scan error = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
  "reader",
  { fps: 30, qrbox: {width: 250, height: 250} },
  /* verbose= */ false);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush