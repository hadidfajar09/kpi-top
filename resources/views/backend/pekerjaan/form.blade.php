
  
  <!-- Modal -->
  <div class="modal fade" id="modal-pekerjaan" tabindex="-1" role="dialog" aria-labelledby="modal-pekerjaan">
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
                <label for="nama_pekerjaan" class="col-md-4 col-md-offset-1 control-label">Nama Pekerjaan</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="nama_pekerjaan" id="nama_pekerjaan" required autofocus>
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
  