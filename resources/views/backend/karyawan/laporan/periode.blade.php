  <!-- Modal -->
  <div class="modal fade" id="modal-periode" tabindex="-1" role="dialog" aria-labelledby="modal-periode">
    <div class="modal-dialog" role="document">

        <form action="{{ route('karyawan.laporan',$id->id) }}" role="form" method="get" class="form-horizontal">
         

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Periode Laporan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           
            <div class="form-group row">
              <label for="tanggal_awal" class="col-lg-4 col-lg-offset-1 control-label">Tanggal Awal</label>
              <div class="col-lg-8">
                  <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control datepicker" required
                      value="{{ request('tanggal_awal')}}"
                      style="border-radius: 0 !important;">
                  <span class="help-block with-errors text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="tanggal_akhir" class="col-lg-4 col-lg-offset-1 control-label">Tanggal Akhir</label>
              <div class="col-lg-8">
                  <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control datepicker" required
                      value="{{ request('tanggal_akhir')}}"
                      style="border-radius: 0 !important;">
                  <span class="help-block with-errors text-danger"></span>
              </div>
          </div>

         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button class="btn btn-warning">Cari</button>
        </div>
      </div>
    </form>
    </div>
  </div>
  