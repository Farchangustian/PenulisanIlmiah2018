<!-- Page Content -->

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <h4>LIHAT ATAU TAMBAH DATA PENGAMBILAN</h4>

    <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-md-7"><b>Data Pengambilan</b></div>
            <div class="col-md-3 text-right">
            <!--form style="margin-bottom: 0px" id="cari" action="<?php echo base_url('admin/search_pengambilan');?>" method="GET">
                <div class="input-group">
                  <input type="text" name="area" id="area" class="form-control input-sm" placeholder="Area" required>
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-secondary btn-sm" type="button">Cari</button>
                  </span>
                </div>
            </form-->
            </div>
            <div class="col-md-2">
              <a onclick="show_pengambilan_form()" class="btn btn-default btn-sm">Tambah Pengambilan</a>
            </div>
        </div>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <!--<th class="text-center">Kd Menu</th>-->
                <th class="text-center">Metode Pengambilan</th>        
                <th class="text-center">Biaya</th>        
                <th class="text-center">Area</th>          
                <th class="text-center">Metode Pembayaran</th>   
                <th class="text-center">Aksi</th>             
            </tr>
        </thead>
        <tbody>
          <?php if (empty($menu)) { ?>
            <tr>
                <td colspan="5" class="text-center">
                    Menu Kosong.
                </td>
            </tr>
        <?php } else {
           $no=1;
           foreach ($menu as $data) {?>
               <tr>
                   <td class="text-center"><?php echo $no;?></td>
                   <td class="text-left"><?php echo $data->metode_pengambilan;?></td>
                   
                   <td class="text-right"><?php echo "Rp. ".number_format($data->biaya, 0, '','.').',-';?></td> 
                   <td class="text-left"><?php echo $data->Area;?></td>
                   <td class="text-left"><?php echo $data->method_pengambilan;?></td>
                   <td class="text-right">
                        <a href="#" onclick="update.call(this);" style="margin-right:5px" class="btn btn-info btn-sm">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>  
                        <a href="#" onclick="hapus.call(this);" class="btn btn-danger btn-sm">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                   </td>
               </tr>
        <?php $no++; }} ?>  
        </tbody>
        </table>
        </div>
    <div class="panel-footer text-center">
        <small>
            <?php echo empty($link)? "Page 1(30)" : $link ; ?>
        </small>
    </div>
    </div>
        </div>
    </div>
        <!-- /#page-content-wrapper -->
</div>
    <!-- /#wrapper -->
</body>
<div class="modal fade" id="lihat-detail-pengambilan" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div style="background-color:#00796B; color: white" class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Detail Pengambilan</h3>
        </div>
        <div class="modal-body">
        <div id="detail-table" class="table-responsive">
            
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">close</button>
        </div>
      </div>      
    </div>
</div>
<div class="modal fade" id="update-pengambilan" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div style="background-color:#00796B; color: white" class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Perbaharui Pengambilan</h3>
        </div>
        <div class="modal-body">
        <div id="form-update">
            
        </div>
        </div>
      </div>      
    </div>
</div>
<script type="text/javascript">
  var detail=function(){
        var currentRow=$(this).closest("tr");
        var id_metode_pengambilan=currentRow.find("td:eq(1)").text();
        /*gettring rady for ajax*/
        var url='lihat_detail_pengambilan/'+id_metode_pengambilan;
        var sendData=$.get(url);
        sendData.done(function(data){
            $("#detail-table").empty().append(data);
            $("#lihat-detail-pengambilan").modal();
        });   
  }
  var update=function(){
        var currentRow=$(this).closest("tr");
        var id_metode_pengambilan=currentRow.find("td:eq(0)").text();
        console.log(id_metode_pengambilan);
        /*gettring rady for ajax*/
        var url='update_pengambilan_form/'+id_metode_pengambilan;
        var sendData=$.get(url);
        sendData.done(function(data){
            $("#form-update").empty().append(data);
            $("#update-pengambilan").modal();
        });   
  }
  var hapus=function(){
    var del = confirm("Anda yakin ingin meghapus data ini?");
    if (del) {
        var currentRow=$(this).closest("tr");
        var id_metode_pengambilan=currentRow.find("td:eq(0)").text();
        /*gettring rady for ajax*/
        var url='delete_pengambilan/'+id_metode_pengambilan;
        var sendData=$.get(url);
        sendData.done(function(data){
            if (data) {
                    alert('Data dihapus!');
                    location.reload();
                }else{
                    alert('Error! hapus data gagal');
            }
        });
    }   
  }
</script>