
<!-- Page Content -->

        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h4>    </h4>
                <div class="panel panel-default">
                <div class="panel-heading">
                <div class="row">
                    <div class="col-md-9"><b>Data Konfirmasi Pembayaran</b></div>
                    <div class="col-md-3">
                    
                          </span>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="display: none" class="text-center">ID</th>
                        <th class="text-center">No. Pesanan</th>
                        <th class="text-center">Bukti Pembayaran</th>
                        <th class="text-center">Status Periksa</th>
                        <th class="text-center">Aksi</th>                
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($konfirmasi)) { ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            Tidak ada pesanan masuk
                        </td>
                    </tr>
                <?php } else {
                   foreach ($konfirmasi as $data) {?>
                       <tr>
                          <td style="vertical-align: middle;display : none" class="text-center"><?php echo $data->no_pesanan;?></td>
                           <td style="vertical-align: middle;" class="text-Center"><?php echo $data->no_pesanan;?></td>
                           <td style="vertical-align: middle;" class="text-center">
                               <a data-key="<?php echo $data->bukti_pembayaran;?>" target="_blank" href="<?php echo base_url($data->bukti_pembayaran);?>">
                                    <img style="width:30px;height:30px" src="<?php echo base_url($data->bukti_pembayaran);?>">
                               </a>
                           </td>
                           <td style="vertical-align: middle;" class="text-center"><?php echo $data->checked==0?'Blm dilihat':'Dilihat';?></td>
                           <td style="vertical-align: middle;" class="text-center">
                                <a target="_blank" href="<?php echo base_url($data->bukti_pembayaran);?>" onclick="" style="margin-right:5px" class="btn btn-info btn-sm">
                                    <span class="glyphicon glyphicon-eye-open"></span>
                                </a>
                                <?php if($data->checked==0){?>
                                <a href="#" onclick="update.call(this);" style="margin-right:5px" class="btn btn-success btn-sm">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <?php } ?>  
                                <a href="#" onclick="hapus.call(this);" class="btn btn-danger btn-sm">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                           </td>
                       </tr>
                <?php }} ?>  
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
<script type="text/javascript">
    var hapus=function(){
        var del = confirm("Anda yakin ingin meghapus data ini?");
        if (del) {
            var currentRow=$(this).closest("tr");
            var id=currentRow.find("td:eq(0)").text();
            var addr=currentRow.find("td:eq(2) > a:eq(0) ").attr('data-key');
            var url='delete_konfirmasi_pesanan/'+id;
            //alert(addr);
            var sendData=$.get(url,{path:addr});
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
    var update=function(){
        var del = confirm("Sudah Periksa pembayaran ini?");
        if (del) {
            var currentRow=$(this).closest("tr");
            var id=currentRow.find("td:eq(0)").text();
            var url='periksa_konfirmasi_pesanan/'+id;
            //alert(addr);
            var sendData=$.get(url);
            sendData.done(function(data){
                alert('Data diperiksa!');
                location.reload();
            });
        }   
    }
</script>