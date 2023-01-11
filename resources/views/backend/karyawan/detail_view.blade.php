@extends('layouts.backend_master')

@section('title')
Daftar Riwayat ABsent
@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb -2">
        <div class="col-sm-6">
          <h1 class="m-0">Daftar Absensi "{{ $karyawan->name }}"</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Absensi</li>
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
              <a class="btn btn-outline-danger btn-sm" href="{{ route('karyawan.grafik',$karyawan->id) }}"><i class="fa fa-plus-circle"></i> Grafik</a>
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
                          <table  class="table table-bordered table-striped dataTable dtr-inline"
                            >
                            <thead>
                              <th width="5%">No</th>
                              <th width="15%">Tanggal</th>
                              <th width="10%">Karyawan</th>
                              <th width="5%">Masuk</th>
                              <th width="10%">Foto</th>
                              <th width="5%">Istirahat</th>
                              <th width="10%">Foto</th>
                              <th width="5%">Pulang</th>
                              <th width="10%">Foto</th>
                              <th width="5%">Kehadiran</th>
                              <th width="5%">Status</th>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                              @foreach($karyawan_detail as $row)
                              <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ formatTanggal($row->created_at) }}</td>
                                <td>{{ $row->karyawan->name }}</td>
                                <td>{{ $row->jam_masuk }}</td>
                                <td><a href="{{ asset($row->foto_masuk) }}" data-toggle="lightbox"><img src="{{ asset($row->foto_masuk) }}" width="50" alt="" class="img-fluid"></a> </td>
                                <td>{{ $row->jam_istirahat }}</td>
                                <td><a href="{{ asset($row->foto_istirahat) }}" data-toggle="lightbox"><img src="{{ asset($row->foto_masuk) }}" width="50" alt="" class="img-fluid"></a> </td>
                                <td>{{ $row->jam_pulang }}</td>
                                <td><a href="{{ asset($row->foto_pulang) }}" data-toggle="lightbox"><img src="{{ asset($row->foto_pulang) }}" width="50" alt="" class="img-fluid"></a> </td>
                                <td>
                                    @if ( $row->status == 0 )
                                    <span class="badge badge-warning">Sakit</span>
                                    @elseif( $row->status == 1)
                                    <span class="badge badge-success">Hadir</span>
                                    @elseif( $row->status == 3)
                                    <span class="badge badge-warning">Izin</span>
                                    @else
                                    <span class="badge badge-danger">Telat</span>
                                    @endif
                                  </td>
                                <td>  @if ( $row->accept == 0 )
                                  <span class="badge badge-danger">Ditolak</span>
                                  @elseif( $row->accept == 1)
                                  <span class="badge badge-success">Diterima</span>
                                  @else
                                  <span class="badge badge-light">Pending</span>
                                  @endif </td>
                              </tr>
                              
                              @endforeach

                            </tbody>
                          </table>
                            
                        </div>
                      </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    {{$karyawan_detail->appends(Request::all())->links('pagination::bootstrap-4')}}
                  </div>
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

@include('backend.absensi.form')

@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script>

      let table;
        $(function(){
          $('body').addClass('sidebar-collapse')
            table = $('.table-agent').DataTable({
              processing: true,
              autoWidth: false,
                ajax: {
                    url: '{{ route('karyawan.detail.data') }}',
                    data: {
                      "id" : $('#id').val()
                    }
                },
                columns: [
                        {data: 'DT_RowIndex', searchable: false, sortable: false},
                        {data: 'tanggal'},
                        {data: 'karyawan'},
                        {data: 'jam_masuk'},
                        {data: 'foto_masuk'},
                        {data: 'jam_istirahat'},
                        {data: 'foto_istirahat'},
                        {data: 'jam_pulang'},
                        {data: 'foto_pulang'},
                        {data: 'status'},
                        {data: 'accept'},
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
            $('#modal-agent .modal-title').text('Ubah Keterangan');

            $('#modal-agent form')[0].reset();
            $('#modal-agent form').attr('action',url);
            $('#modal-agent [name=_method]').val('put');
            $('#modal-agent [name=status]').focus();

            $.get(url)
              .done((response) => {
                $('#modal-agent [name=status]').val(response.status);
                $('#modal-agent [name=keterangan]').val(response.keterangan);

              })

              .fail((errors) => {
                alert('Data tidak ditemukan');
                return;
              })
        }

        function deleteData(url) {
          if(confirm('Yakin Ingin Hapus Groomming Ini?')){
            
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