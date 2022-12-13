
  
  <!-- Modal -->
  <div class="modal fade" id="modal-kontrak" tabindex="-1" role="dialog" aria-labelledby="modal-kontrak">
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
                <label for="kontrak" class="col-md-4 col-md-offset-1 control-label">Masa Kontrak</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="kontrak" id="kontrak" required autofocus>
                    <span class="text-danger">*Contoh : 1 bulan, 2 bulan</span>
                    <span class="help-block with-errors text-danger"></span>

                </div>
            </div>

            <div class="form-group row">
              <label for="deskripsi" class="col-md-4 col-md-offset-1 control-label">Deskripsi</label>
              <div class="col-md-8">
                  <textarea class="form-control" name="deskripsi" id="" cols="30" rows="5"></textarea>
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
  