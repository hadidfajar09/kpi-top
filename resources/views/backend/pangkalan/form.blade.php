
  
  <!-- Modal -->
  <div class="modal fade" id="modal-pangkalan" tabindex="-1" role="dialog" aria-labelledby="modal-pangkalan">
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
            <label for="name" class="col-md-4 col-md-offset-1 control-label">Nama Pangkalan</label>
            <div class="col-md-8">
                <input class="form-control" type="text" name="name" id="name" required autofocus>
                <span class="help-block with-errors text-danger"></span>

            </div>
        </div>

        
        <div class="form-group row">
          <label for="alamat" class="col-md-4 col-md-offset-1 control-label">Alamat</label>
          <div class="col-md-8">
            <textarea class="form-control" name="alamat" id="alamat" rows="3" required> </textarea>
                        <span class="help-block with-errors"></span>
          </div>
        </div>

        <div class="form-group row">
          <label for="id_agent" class="col-md-4 col-md-offset-1 control-label">Nama Agent</label>
          <div class="col-md-8">
            <select class="form-control" name="id_agent" id="id_agent" required>
              <option value="">Pilih Agent</option>
              @foreach ($agent as $key => $row)
                  <option value="{{ $key }}">{{ $row }}</option>
              @endforeach

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
          <label for="stock_tabung" class="col-md-4 col-md-offset-1 control-label">Stock Tabung</label>
          <div class="col-md-8">
              <input class="form-control" type="number" name="stock_tabung" id="stock_tabung" required>
              <span class="help-block with-errors text-danger"></span>

          </div>
      </div>


            <div class="form-group row">
                <label for="id_kecamatan" class="col-md-4 col-md-offset-1 control-label">Nama Kecamatan</label>
                <div class="col-md-8">
                  <select class="form-control" name="id_kecamatan" id="id_kecamatan" required>
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($kecamatan as $key => $row)
                        <option value="{{ $key }}">{{ $row }}</option>
                    @endforeach

                  </select>
                  <span class="help-block with-errors text-danger"></span>
                </div>
            </div>

            <div class="form-group row">
              <label for="id_desa" class="col-md-4 col-md-offset-1 control-label">Nama Desa</label>
              <div class="col-md-8">
                <select class="form-control" name="id_desa" id="id_desa" style="width:100%" data-select2-id="1" tabindex="-1" aria-hidden="true" required>
                  <option value="">Pilih Desa</option>
                </select>
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
  