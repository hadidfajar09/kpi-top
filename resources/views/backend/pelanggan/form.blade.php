
  
  <!-- Modal -->
  <div class="modal fade" id="modal-pelanggan" tabindex="-1" role="dialog" aria-labelledby="modal-pelanggan">
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
            <label for="nama_pelanggan" class="col-md-4 col-md-offset-1 control-label">Nama Pelanggan</label>
            <div class="col-md-8">
                <input class="form-control" type="text" name="nama_pelanggan" id="nama_pelanggan" required autofocus>
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
          <label for="nik" class="col-md-4 col-md-offset-1 control-label">NIK</label>
          <div class="col-md-8">
              <input class="form-control" type="number" name="nik" id="nik" required autofocus>
              <span class="help-block with-errors text-danger"></span>

          </div>
      </div>

      <div class="form-group row">
        <label for="no_kk" class="col-md-4 col-md-offset-1 control-label">No KK</label>
        <div class="col-md-8">
            <input class="form-control" type="number" name="no_kk" id="no_kk" required autofocus>
            <span class="help-block with-errors text-danger"></span>

        </div>
    </div>

        <div class="form-group row">
          <label for="id_pekerjaan" class="col-md-4 col-md-offset-1 control-label">Pekerjaan</label>
          <div class="col-md-8">
            <select class="form-control" name="id_pekerjaan" id="id_pekerjaan" required>
              <option value="">Pilih Pekerjaan</option>
              @foreach ($pekerjaan as $key => $row)
                  <option value="{{ $key }}">{{ $row }}</option>
              @endforeach

            </select>
            <span class="help-block with-errors text-danger"></span>
          </div>
      </div>

      <div class="form-group row">
        <label for="id_penghasilan" class="col-md-4 col-md-offset-1 control-label">Penghasilan/Bulan</label>
        <div class="col-md-8">
          <select class="form-control" name="id_penghasilan" id="id_penghasilan" required>
            <option value="">Pilih Nominal</option>
            @foreach ($penghasilan as $key => $row)
                <option value="{{ $key }}">Rp {{ formatUang($row)  }}</option>
            @endforeach

          </select>
          <span class="help-block with-errors text-danger"></span>
        </div>
    </div>
             
          <div class="form-group row">
            <label for="jumlah_tabung" class="col-md-4 col-md-offset-1 control-label">Jumlah Tabung</label>
            <div class="col-md-8">
                <input class="form-control" type="number" name="jumlah_tabung" id="jumlah_tabung" required>
                <span class="help-block with-errors text-danger"></span>

            </div>
        </div>

        <div class="form-group row">
          <label for="jatah_bulanan" class="col-md-4 col-md-offset-1 control-label">Jatah Bulanan</label>
          <div class="col-md-8">
              <input class="form-control" type="number" name="jatah_bulanan" id="jatah_bulanan" required>
              <span class="help-block with-errors text-danger"></span>

          </div>
      </div>
    

        <div class="form-group row">
          <label for="id_pangkalan" class="col-md-4 col-md-offset-1 control-label">Nama Pangkalan</label>
          <div class="col-md-8">
            <select class="form-control" name="id_pangkalan" id="id_pangkalan" required>
              <option value="">Pilih Pangkalan</option>
              @foreach ($pangkalan as $key => $row)
                  <option value="{{ $key }}">{{ $row }}</option>
              @endforeach

            </select>
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
     

         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button class="btn btn-warning">Simpan</button>
        </div>
      </div>
    </form>
    </div>
  </div>
  