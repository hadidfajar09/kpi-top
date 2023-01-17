@extends('layouts.backend_master')

@section('title')
Laporan Daily 
@endsection

@push('css')
      <!-- daterange picker -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
@endpush

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h1>
            Laporan Daily Activity {{ formatTanggal($tanggalAwal,false) }} s/d {{ formatTanggal($tanggalAkhir,false) }}
          </h1>
        </div><!-- /.col -->
        <div class="col-sm-2">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Laporan Daily</li>
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
              <button class="btn btn-outline-warning btn-sm" onclick="updatePeriode('{{ route('karyawan.laporan',$id->id) }}')"" ><i class="fa fa-plus-circle"></i> Ubah Periode</button>

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
                          <table  class="table table-bordered table-striped dataTable dtr-inline table-laporan"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th width="10%">Tanggal</th>
                              <th width="10%">Absen</th>
                              <th width="10%">Grooming</th>
                              <th width="10%">Kebersihan</th>
                              <th width="10%">Briefing</th>
                              <th width="10%">Omset</th>
                              <th width="10%">COD</th>
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

@include('backend.karyawan.laporan.periode')
@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>

      let table;
        $(function(){
          $('body').addClass('sidebar-collapse')
            table = $('.table-laporan').DataTable({
              responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
                ajax: {
                    url: '{{ route('karyawan.laporan', [$tanggalAwal, $tanggalAkhir, $id]) }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'tanggal'},
                        {data: 'stock_pangkalan'},
                        {data: 'transaksi_pelanggan'},
                        {data: 'sisa_stock'},
                ],
                dom: 'Brt',
                bSort: true,
                bPaginate: false,

            });

            table.button(0).nodes().css('background', '#fd7e14');
            table.button(1).nodes().css('background', '#fd7e14');
            table.button(2).nodes().css('background', '#fd7e14');
            table.button(3).nodes().css('background', '#fd7e14');
            table.button(4).nodes().css('background', '#fd7e14');

         
          });

          function updatePeriode(){
            $('#modal-periode').modal('show');
            
        }

            $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

    </script>
@endpush