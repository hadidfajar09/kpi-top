
  
  <!-- Modal -->
  <div class="modal fade" id="modal-distribusi" tabindex="-1" role="dialog" aria-labelledby="modal-distribusi">
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
            <label for="tanggal_pengantaran" class="col-md-4 col-md-offset-1 control-label">Tanggal Distribusi</label>
            <div class="col-md-8">
                <input class="form-control datepicker" type="text" name="tanggal_pengantaran" id="tanggal_pengantaran" required>
                <span class="help-block with-errors text-danger"></span>

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
        <label for="id_pangkalan" class="col-md-4 col-md-offset-1 control-label">Nama Pangkalan</label>
        <div class="col-md-8">
          <select class="form-control" name="id_pangkalan" id="id_pangkalan" style="width:100%" data-select2-id="1" tabindex="-1" aria-hidden="true" required>
            <option value="">Pilih Pangkalan</option>
          </select>
          <span class="help-block with-errors text-danger"></span>
        </div>
    </div>
             
       

       

        <div class="form-group row">
          <label for="drop_tabung" class="col-md-4 col-md-offset-1 control-label">Drop Tabung</label>
          <div class="col-md-8">
              <input class="form-control" type="number" name="drop_tabung" id="drop_tabung" required>
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
  