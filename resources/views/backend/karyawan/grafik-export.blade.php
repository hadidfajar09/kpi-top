<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Daily Grafik</title>
       <!-- Font Awesome Icons -->
      
 
  <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/daterangepicker/daterangepicker.css') }}">
   <!-- DataTables -->
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- overlayScrollbars -->
  

  <link rel="stylesheet" href="{{ asset('backend_pemda/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('backend_pemda/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
</head>
<body>
    <h3 class="text-center">Laporan Daily Grafik Karyawan | {{ $karyawan->name }} - {{ $karyawan->jabatan->jabatan }}</h3>
   
    <section class="content">
        <div class="container-fluid">
  
            
          
          <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Grafik Daily Report {{ formatTanggal($tanggal_awal,false) }} s/d {{ formatTanggal($tanggal_akhir,false) }}</h5>
    
                   
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="chart">
                          <!-- Sales Chart Canvas -->
                          <canvas id="salesChart1" height="50" ></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                      </div>
                      <!-- /.col -->
                    
                      <!-- /.col -->
                    </div>
    
                    <div class="row d-flex justify-content-center">
                      <div class="col-md-6">
                      <div class="card card-primary">
                        <div class="card-header">
                          <h3 class="card-title">Absensi Bulan ini</h3>
          
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.col -->
                    
                      <!-- /.col -->
                    </div>
  
                 
    
                  </div>
                    <!-- /.row -->
                    <div class="row">
                      <div class="col-md-6">
                      <div class="card card-pink">
                        <div class="card-header">
                          <h3 class="card-title">Briefing Bulan ini</h3>
          
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="pieChart3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.col -->
                    
                      <!-- /.col -->
                    </div>
  
                    <div class="col-md-6">
                      <div class="card card-olive">
                        <div class="card-header">
                          <h3 class="card-title">Cleaning Bulan Ini</h3>
          
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button>
                          </div>
                        </div>
                        <div class="card-body">
                          <canvas id="pieChart4" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                      </div>
                      <!-- /.col -->
                    
                      <!-- /.col -->
                    </div>
    
                  </div>
  
                  <div class="row">
                    <div class="col-md-6">
                    <div class="card card-cyan">
                      <div class="card-header">
                        <h3 class="card-title">COD Bulan ini</h3>
        
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <canvas id="pieChart5" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.col -->
                  
                    <!-- /.col -->
                  </div>
  
                  <div class="col-md-6">
                    <div class="card card-olive">
                      <div class="card-header">
                        <h3 class="card-title">Omset Bulan Ini</h3>
        
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <canvas id="pieChart6" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.col -->
                  
                    <!-- /.col -->
                  </div>
  
                </div>
                  </div>
                  <!-- ./card-body -->
                 
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.col -->
          <!-- /.row -->
  
          <!-- Main row -->
        
          <!-- /.row -->
        </div><!--/. container-fluid -->
      </section>
   
     <!-- jQuery -->
<script src="{{ asset('backend_pemda/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend_pemda/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('backend_pemda/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('backend_pemda/dist/js/adminlte.js') }}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{ asset('backend_pemda/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>



<!-- DataTables  & Plugins -->
<script src="{{ asset('backend_pemda/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend_pemda/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

 
 <script>
 
   $('body').addClass('sidebar-collapse');
   
   $(function(){
     var salesChartCanvas = $('#salesChart1').get(0).getContext('2d');
 
 var salesChartData = {
   labels: {{ json_encode($data_tanggal) }},
   datasets: [
 
     {
         label: 'Briefing',
         backgroundColor: 'rgba(157, 0, 0, 0.8)',
         borderColor: 'rgba(210, 214, 222, 1)',
         pointRadius: false,
         pointColor: 'rgba(210, 214, 222, 1)',
         pointStrokeColor: '#c1c7d1',
         pointHighlightFill: '#fff',
         pointHighlightStroke: 'rgba(220,220,220,1)',
         data: {{ json_encode($total_stock_keluar) }}
       }, 
       
       {
         label: 'Cleaning',
         backgroundColor: '#873e23',
         borderColor: 'rgb(135,62,35,1)',
         pointRadius: false,
         pointColor: 'rgb(135,62,35,1)',
         pointStrokeColor: '#c1c7d1',
         pointHighlightFill: '#fff',
         pointHighlightStroke: 'rgba(220,220,220,1)',
         data: {{ json_encode($total_cleaning) }}
       },    
       {
         label: 'Absensi',
         backgroundColor: '#2596be',
         borderColor: 'rgb(135,62,35,1)',
         pointRadius: false,
         pointColor: 'rgb(135,62,35,1)',
         pointStrokeColor: '#c1c7d1',
         pointHighlightFill: '#fff',
         pointHighlightStroke: 'rgba(220,220,220,1)',
         data: {{ json_encode($total_absen) }}
       },    
 
    
   ]
 };
 
 var salesChartOptions = {
     maintainAspectRatio: true,
     responsive: true,
     legend: {
       display: true
     },
     scales: {
       xAxes: [{
         gridLines: {
           display: true
         }
       }],
       yAxes: [{
         gridLines: {
           display: true
         }
       }]
     }
   };
 
   // This will get the first returned node in the jQuery collection.
   // eslint-disable-next-line no-unused-vars
   var salesChart = new Chart(salesChartCanvas, {
     type: 'bar',
     data: salesChartData,
     options: salesChartOptions
   }
   );
 
 
   //-------------
 
 
 
   });
 
   var pieChart = new Chart($('#pieChart'), {
         type: 'pie',
         data: {
             labels: ['Tepat Waktu', 'Telat', 'Izin','Sakit'],
             datasets: [{
                 label: 'Absen%',
                 data: [{{ json_encode($absensi_diterima) }}, {{ json_encode($absensi_telat) }}, {{ json_encode($absensi_izin) }}, {{ json_encode($absensi_sakit) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12','#2d3084  '],
             }]
         },
         options: {
             responsive: true
         }
     });
 
     var pieChart = new Chart($('#pieChart2'), {
         type: 'pie',
         data: {
             labels: ['Diterima', 'Ditolak', 'Pending'],
             datasets: [{
                 label: 'Grooming',
                 data: [{{ json_encode($grooming_diterima) }}, {{ json_encode($grooming_ditolak) }}, {{ json_encode($grooming_pending) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
             }]
         },
         options: {
             responsive: true
         }
     });
 
     var pieChart = new Chart($('#pieChart3'), {
         type: 'pie',
         data: {
             labels: ['Diterima', 'Ditolak', 'Pending'],
             datasets: [{
                 label: 'Briefing',
                 data: [{{ json_encode($briefing_diterima) }}, {{ json_encode($briefing_ditolak) }}, {{ json_encode($briefing_pending) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
             }]
         },
         options: {
             responsive: true
         }
     });
 
     var pieChart = new Chart($('#pieChart4'), {
         type: 'pie',
         data: {
             labels: ['Diterima', 'Ditolak', 'Pending'],
             datasets: [{
                 label: 'Cleaning',
                 data: [{{ json_encode($cleaning_diterima) }}, {{ json_encode($cleaning_ditolak) }}, {{ json_encode($cleaning_pending) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
             }]
         },
         options: {
             responsive: true
         }
     });
     var pieChart = new Chart($('#pieChart5'), {
         type: 'pie',
         data: {
           labels: ['Diterima', 'Ditolak', 'Pending'],
             datasets: [{
                 label: 'Cod',
                 data: [{{ json_encode($cod_diterima) }}, {{ json_encode($cod_ditolak) }}, {{ json_encode($cod_pending) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
             }]
         },
         options: {
             responsive: true
         }
     });
     var pieChart = new Chart($('#pieChart6'), {
         type: 'pie',
         data: {
           labels: ['Diterima', 'Ditolak', 'Pending'],
             datasets: [{
                 label: 'Absen',
                 data: [{{ json_encode($omset_diterima) }}, {{ json_encode($omset_ditolak) }}, {{ json_encode($omset_pending) }}],
                 backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
             }]
         },
         options: {
             responsive: true
         }
     });
 
     
 
 </script>
</body>
</html>