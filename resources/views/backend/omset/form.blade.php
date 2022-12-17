
  
  <!-- Modal -->
  <div class="modal fade" id="modal-agent" tabindex="-1" role="dialog" aria-labelledby="modal-agent">
    <div class="modal-dialog" role="document">

        <form action="" role="form" method="post" class="form-horizontal" data-toggle="validator">
            @csrf
            @method('post') 

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           
            <div class="form-group row">
                <label for="jabatan" class="col-md-4 col-md-offset-1 control-label">Tanggal</label>
                <div class="col-md-8">
                    <input class="form-control" type="date" name="tanggal_setor" id="tanggal_setor" required autofocus>
                    <span class="help-block with-errors text-danger"></span>

                </div>
            </div>

            <div class="form-group row">
              <label for="jabatan" class="col-md-4 col-md-offset-1 control-label">Sales</label>
              <div class="col-md-8">
                <select class="form-control" name="karyawan_id" id="karyawan_id" required>
                  <option value="">Pilih Sales</option>
                  @foreach ($sales as $key => $row)
                      <option value="{{ $key }}">{{ $row }}</option>
                  @endforeach
    
                </select>
                  <span class="help-block with-errors text-danger"></span>

              </div>
          </div>

          <div class="form-group row">
            <label for="jabatan" class="col-md-4 col-md-offset-1 control-label">Nominal</label>
            <div class="col-md-8">
                <input class="form-control" type="number" name="nominal" id="nominal" required autofocus>
                <span class="help-block with-errors text-danger"></span>

            </div>
        </div>

            <div class="form-group row">
              <label for="deskripsi" class="col-md-4 col-md-offset-1 control-label">Catatan</label>
              <div class="col-md-8">
                  <textarea class="form-control" name="catatan" id="" cols="30" rows="5"></textarea>
                  <span class="help-block with-errors text-danger"></span>
  
              </div>
          </div>
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button class="btn btn-warning">Simpan</button>
        </div>
      </div>
    </form>
    </div>
  </div>
  