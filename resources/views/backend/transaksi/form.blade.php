
  
  <!-- Modal -->
  <div class="modal fade" id="modal-transaksi" tabindex="-1" role="dialog" aria-labelledby="modal-transaksi">
    <div class="modal-dialog modal-lg" role="document">

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
            <label for="nama_desa" class="col-md-4 col-md-offset-1 control-label">SCAN QRCODE</label>
            <div class="col-md-8">
                
            </div>
        </div>
          
               <center>
                    <div id="reader" style="width: 600px;">
                      
                    </center>
                    <input class="form-control" type="text" id="result">
                  
                  
          

            
       

         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button class="btn btn-warning">Simpan</button>
        </div>
      </div>
    </form>
    </div>
  </div>
  