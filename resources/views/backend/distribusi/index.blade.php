@extends('layouts.backend_master')

@section('title')
Riwayat Distribusi Agen
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
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Distribusi Agen</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Distribusi</li>
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

              <button class="btn btn-outline-warning btn-sm" onclick="addForm('{{ route('distribusi.store') }}')"" ><i class="
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
                        <table class="table table-bordered table-striped dataTable dtr-inline table-distribusi">
                          <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Agent</th>
                            <th>Pangkalan</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
                            <th>Drop Tabung</th>
                            <th>Status</th>
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

@include('backend.distribusi.form')
@endsection


@push('scripts')
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
  let table;
        $(function(){
          $('body').addClass('sidebar-collapse')
            table = $('.table-distribusi').DataTable({
              processing: true,
              autoWidth: false,
              aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
                ajax: {
                    url: '{{ route('distribusi.data') }}'
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'tanggal_pengantaran'},
                        {data: 'nama_agent'},
                        {data: 'nama_pangkalan'},
                        {data: 'kecamatan'},
                        {data: 'desa'},
                        {data: 'drop_tabung'},
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

            $('#modal-distribusi').validator().on('submit', function(e){
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-distribusi form').attr('action'),
                        type: 'post',
                        data: $('#modal-distribusi form').serialize()
                    })
                    .done((response) => {
                        $('#modal-distribusi').modal('hide');
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
            $('#modal-distribusi').modal('show');
            $('#modal-distribusi .modal-title').text('Tambah Distribusi');

            $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
            });

            $('#modal-distribusi form')[0].reset();
            $('#modal-distribusi form').attr('action',url);
            $('#modal-distribusi [name=_method]').val('post');
            $('#modal-distribusi [name=name]').focus();

           
        }

        function editForm(url){
            $('#modal-distribusi').modal('show');
            $('#modal-distribusi .modal-title').text('Ubah Distribusi');

            $('#modal-distribusi form')[0].reset();
            $('#modal-distribusi form').attr('action',url);
            $('#modal-distribusi [name=_method]').val('put');
            $('#modal-distribusi [name=name]').focus();


            $.get(url)
              .done((response) => {
                $('#modal-distribusi [name=tanggal_pengantaran]').val(response.tanggal_pengantaran);
                $('#modal-distribusi [name=id_agent]').val(response.id_agent);
                $('#modal-distribusi [name=id_pangkalan]').val(response.id_pangkalan);
                $('#modal-distribusi [name=drop_tabung]').val(response.drop_tabung);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        

        function deleteData(url) {

          if(confirm('Yakin Ingin Hapus Distribusi Ini?')){
           
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
        $('select[name="id_agent"]').on('change', function(){
            var id_agent = $(this).val();
            if(id_agent) {
                $.ajax({
                    url: "{{  url('/get/pangkalan/') }}/"+id_agent,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                       $("#id_pangkalan").empty();
                             $.each(data,function(key,value){
                                 $("#id_pangkalan").append('<option value="'+value.id+'">'+value.name+'</option>');
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
