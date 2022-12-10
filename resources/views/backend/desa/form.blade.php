
  
  <!-- Modal -->
  <div class="modal fade" id="modal-desa" tabindex="-1" role="dialog" aria-labelledby="modal-desa">
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
                <label for="id_kecamatan" class="col-md-4 col-md-offset-1 control-label">Nama Kecamatan</label>
                <div class="col-md-8">
                  <select class="form-control" name="id_kecamatan" id="id_kecamatan" required autofocus>
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatan as $key => $row)
                        <option value="{{ $key }}">{{ $row }}</option>
                    @endforeach

                  </select>
                  <span class="help-block with-errors text-danger"></span>
                </div>
            </div>

            <div class="form-group row">
              <label for="nama_desa" class="col-md-4 col-md-offset-1 control-label">Nama Desa/Kelurahan</label>
              <div class="col-md-8">
                  <input class="form-control" type="text" name="nama_desa" id="nama_desa" required autofocus>
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
  