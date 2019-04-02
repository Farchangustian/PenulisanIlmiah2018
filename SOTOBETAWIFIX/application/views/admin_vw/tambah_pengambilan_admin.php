<div class="modal fade" id="tambah_pengambilan" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div style="background-color:#00796B; color: white" class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Tambah Data Pengambilan</h3>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('admin/tambah_pengambilan');?>" enctype="multipart/form-data" method="POST">
        	<div class="form-group">
        		<label>Metode Pengambilan</label>
        		<select name="metode_pengambilan" class="form-control" required>
        			<option value="">-- Pilih --</option>
        			<option value="Antar">Antar</option>
        			<option value="Ambil Sendiri">Ambil Sendiri</option>
        		</select>
        	</div>
        	<div class="form-group">
        		<label>Biaya</label>
        		<input type="number" name="biaya" class="form-control input-sm" required>
        	</div>
        	<div class="form-group">
        		<label>Area</label>
        		<input type="text" name="area" class="form-control input-sm" required>
        	</div>
        	<div class="form-group">
        		<label>Metode Pembayaran</label>
        		<select name="method_pengambilan" class="form-control" required>
        			<option value="">-- Pilih --</option>
        			<option value="transfer">transfer</option>
        			<option value="cash">cash</option>
        		</select>
        	</div>     
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Tambah</button>
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
        </div>
      </div>      
    </div>
</div>
<script type="text/javascript">
	function show_pengambilan_form(){
        $("#tambah_pengambilan").modal();   
    }   
    
</script>