
  
  <!-- Modal -->
  <div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="modal-user">
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
                <label for="name" class="col-md-4 col-md-offset-1 control-label">Nama</label>
                <div class="col-md-8">
                    <input class="form-control" type="text" name="name" id="name" required autofocus>
                    <span class="help-block with-errors text-danger"></span>

                </div>
            </div>

            <div class="form-group row">
              <label for="level" class="col-md-4 col-md-offset-1 control-label">Hak Akses</label>
              <div class="col-md-8">
                <select class="form-control" name="level" id="level" required>
                  <option value="" disabled>=====</option>
                 
                      <option value="3">HRD</option>
                      <option value="4">LEADER</option>
                      <option value="5">SPV</option>
    
                </select>
                <span class="help-block with-errors text-danger"></span>
              </div>
          </div> 

            <div class="form-group row">
              <label for="email" class="col-md-4 col-md-offset-1 control-label">Email</label>
              <div class="col-md-8">
                  <input class="form-control" type="text" name="email" id="email" required>
                  <span class="help-block with-errors text-danger"></span>
  
              </div>
          </div>

            
          <div class="form-group row">
            <label for="password" class="col-md-4 col-md-offset-1 control-label">Password</label>
            <div class="col-md-8">
                <input class="form-control" type="password" name="password" id="password" required>
                <span class="help-block with-errors text-danger"></span>
  
            </div>
          </div>     
  
          <div class="form-group row">
            <label for="password_confirmation" class="col-md-4 col-md-offset-1 control-label">Password Konfirmasi</label>
            <div class="col-md-8">
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required data-match="#password">
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
  