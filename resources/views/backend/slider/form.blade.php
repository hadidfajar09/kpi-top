
  
  <!-- Modal -->
  <div class="modal fade" id="modal-slider" tabindex="-1" role="dialog" aria-labelledby="modal-slider">
    <div class="modal-dialog" role="document">

        <form  method="post" class="form-horizontal" data-toggle="validator"  enctype="multipart/form-data">
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
          <a href="'.route('file.download', $karyawan->id).'" class="btn btn-xs btn-warning btn-flat" target="_blank"><i class="fa fa-download"></i></a>
            <div class="form-group row">
                <label for="path_slider" class="col-md-4 col-md-offset-1 control-label">Banner</label>
                <div class="col-md-8">
                  <input type="file" class="form-control" name="path_slider" id="path_slider" onchange="preview('.tampil-slider', this.files[0])">
                  @error('path_slider')
                  <span class="help-block with-errors text-danger">wajib jpg</span>
                  @enderror
                  <br>
                  <div class="tampil-slider" name="path_slider"></div>

                </div>
            </div>

            <div class="form-group row">
              <label for="nama_slider" class="col-md-4 col-md-offset-1 control-label">Keterangan</label>
              <div class="col-md-8">
                  <input class="form-control" type="text" name="nama_slider" id="nama_slider" required>
                  <span class="help-block with-errors text-danger"></span>
  
              </div>
          </div>

            
          <div class="form-group row">
            <label for="level" class="col-md-4 col-md-offset-1 control-label">Pilih User</label>
            <div class="col-md-8">
              <select class="form-control" name="level" id="level" required>
                <option value="" disabled>Pilih User</option>
               
                    <option value="1">Agent</option>
                    <option value="2">Pangkalan</option>
                
  
              </select>
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
  