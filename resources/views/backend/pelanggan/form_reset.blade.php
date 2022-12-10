
  
  <!-- Modal -->
  <div class="modal fade" id="modal-reset" tabindex="-1" role="dialog" aria-labelledby="modal-reset">
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
           
        Yakin Ingin Reset Jumlah Tabung?
         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button class="btn btn-warning">Yakin</button>
        </div>
      </div>
    </form>
    </div>
  </div>
  