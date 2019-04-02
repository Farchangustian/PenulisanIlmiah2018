<!-- Page Content -->
<?php $bln=$this->input->get('bln');
  if (is_null($bln)) {
    $bln=date("m");
  }
?>
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <h4>LAPORAN PENJUALAN</h4>

    <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-md-5"><b>Data Laporan Penjualan</b></div>
            <div class="col-md-7 text-right">
              <a id="cetak" class="btn btn-default btn-sm">Cetak</a>
            </div>
           <!-- <div class="col-md-2">
             <form style="margin-bottom: 0px" id="cari" action="<?php echo base_url('admin/lihat_laporan_penjualan');?>" method="GET">
                <select id="bln" name="bln" class="form-control input-sm" placeholder="bulan">
                    <option> Bulan</option>
                    <option <?php echo $bln=="01"?"selected":"";?> value='01'>Januari</option>
                    <option <?php echo $bln=="02"?"selected":"";?> value='02'>Februari</option>
                    <option <?php echo $bln=="03"?"selected":"";?> value='03'>Maret</option>
                    <option <?php echo $bln=="04"?"selected":"";?> value='04'>April</option>
                    <option <?php echo $bln=="05"?"selected":"";?> value='05'>Mei</option>
                    <option <?php echo $bln=="06"?"selected":"";?> value='06'>Juni</option>
                    <option <?php echo $bln=="07"?"selected":"";?> value='07'>Juli</option>
                    <option <?php echo $bln=="08"?"selected":"";?> value='08'>Agustus</option>
                    <option <?php echo $bln=="09"?"selected":"";?> value='09'>September</option>
                    <option <?php echo $bln=="10"?"selected":"";?> value='10'>Oktober</option>
                    <option <?php echo $bln=="11"?"selected":"";?> value='11'>November</option>
                    <option <?php echo $bln=="12"?"selected":"";?> value='12'>Desember</option>
                </select>
            </div>
            <div class="col-md-2">
                    <select id="tahun" name="tahun" class="form-control input-sm">
                    <option value="">Tahun</option>
                    <script type="text/javascript">
                      function getParameterByName(name, url) {
                          if (!url) {
                            url = window.location.href;
                          }
                          name = name.replace(/[\[\]]/g, "\\$&");
                          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                              results = regex.exec(url);
                          if (!results) return null;
                          if (!results[2]) return '';
                          return decodeURIComponent(results[2].replace(/\+/g, " "));
                      }
                      var year= new Date();
                      var thisyear=getParameterByName('tahun');
                      if (thisyear==null) {
                        thisyear=year.getFullYear();
                      }
                      var startyear=2000;
                      for (var i = 0; i <= 30; i++) {
                        if (Number(startyear+i)==thisyear) {
                             document.write("<option selected value='"+ Number(startyear+i) +"'>"+ Number(startyear+i) +"</option>");   
                        }else{
                             document.write("<option value='"+ Number(startyear+i) +"'>"+ Number(startyear+i) +"</option>"); 
                        }       
                      }
                    </script>
                    </select>
            </div> -->
            <!--<div class="col-md-3 text-right">
                <button type="submit" class="btn btn-default btn-sm" type="button">Lihat Penjualan</button>
              </form>-->
              </div>
        </div>
        </div>
        </div>
        <div class="table-responsive">
        <table id="data_export" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">nama user</th>
                <th class="text-center">No. Pesanan</th>
                <th class="text-center">Tgl pesanan</th>
                <th class="text-center">Total</th>
                <!--<th class="text-center">Hapus</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
                $laporan = $this->db->get_where('ttrans_pesanan', array('id_status_pembayaran' => '1'));
                $laporan = $laporan->result();
                      
                $no = 0;
                foreach($laporan as $detail) {
                    $user = $this->db->get_where('tmas_user_detail', array('id_user' => $detail->id_user));
                    $user = $user->result()[0];

                    $no++;

                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$user->name</td>";
                    echo "<td>$detail->no_pesanan</td>";
                    echo "<td>$detail->tgl_pesan</td>";
                    echo "<td>Rp. $detail->total_harga</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
        </table>
        </div>
    </div>
        </div>
    </div>
        <!-- /#page-content-wrapper -->
</div>
        <!-- /#wrapper -->
        <div class="modal fade" id="lihat-detail-pesanan" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div style="background-color:#00796B; color: white" class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title">Detail</h3>
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
    <div class="modal fade" id="update_status_pesanan" role="dialog">
        <div class="modal-dialog">
    </div>
</body>
<script type="text/javascript" src="<?php echo base_url('lib/js/tableexport.js');?>"></script>
<script type="text/javascript">
    $("table").tableExport({
       formats: ["xls"],
       position: "bottom"
    });
    $('#cetak').click(function(){
      $(".xls").click();
    });

    var detail=function(){
        var currentRow=$(this).closest("tr");
        var no_pesanan=currentRow.find("td:eq(2)").text();
        var id_user=currentRow.find("td:eq(1)").text();
        var tgl_pesan=currentRow.find("td:eq(3)").text();
        var tgl_ambil=currentRow.find("td:eq(4)").text();
        var metode_ambil=currentRow.find("td:eq(5)").text();
        var status_bayar=currentRow.find("td:eq(6)").text();      
        /*gettring rady for ajax*/
        var url='lihat_detail_pesanan/'+no_pesanan;
        var sendData=$.get(url, {id_user:id_user});
        sendData.done(function(data){
            $("#detail-table").empty().append(data);
            $("#lihat-detail-pesanan").modal();
        });   
    }
/*var hapus=function(){
        var del = confirm("Anda yakin ingin meghapus data ini?");
        if (del) {
            var currentRow=$(this).closest("tr");
            var data_export=currentRow.find("td:eq(1)").text();
            var url='lihat_laporan_penjualan/'+data_export;
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
    }*/
    var update=function(){
        var currentRow=$(this).closest("tr");
        var data_export=currentRow.find("td:eq(1)").text();
        $("#no").val(data_export);
        $("#lihat_laporan_penjualan").modal();
    } 
</script>
<style type="text/css">
  caption.btn-toolbar.bottom {
    display: none;
  }
</style>