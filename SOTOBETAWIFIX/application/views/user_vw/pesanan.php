
<?php
	defined('BASEPATH') or exit('No direct script allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Lihat Pesanan</title>
	<style type="text/css">
		thead{
			background-color: #2196f3;
		}
		th{
			text-align: center;
			font-weight: lighter;
		}
		td{
			font-weight: lighter;
			font-size: 10pt;
		}
		.kosong{
			height: 310px;
			line-height: 310px;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<h3>Riwayat Pesanan</h3>
		<div class="table-responsive">
		 <table class="table table-condensed table-bordered bordered">
		    <thead>
		      <tr>
		        <th>No</th>
		        <th>No Tagihan</th>
		        <!--<th>Total Harga</th>-->
		        <th>Tgl Pemesanan</th>
		        <th>Tgl Pengambilan</th>
		        <th>Metode Pengambilan</th>
		        <th>Status Pesanan</th>
		        <th>Status Pembayaran</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php
		      	if (empty($data)) { ?>
		      		<tr class="kosong">
		      			<td colspan="10" class="text-center">Tidak ada riwayat pemesanan.</td>
		      		</tr>
		      	<?php }else{
					$no=1;
		      	foreach ($data as $data) { ?>
		      	<tr>
		      		<td class="text-center"><?php echo $no;?></td>
		      		<td><a href="javascript:void();" onclick="detail('<?php echo $data->no_pesanan ?>');" data-toggle="modal" data-target="#lihat-detail-pesanan"><?php echo $data->no_pesanan?></a></td>
		      		<!--<td class="text-right"><?php echo "Rp. ".number_format($data->harga_total, 0, '','.').',-';?></td>-->
		      		<td class="text-center"><?php echo $data->tgl_pesan;?></td>
		      		<td class="text-center"><?php echo $data->tgl_ambil;?></td>
		      		<td class="text-center"><?php echo $data->id_metode_pengambilan == 0?"Ambil sendiri":"Jasa antar";?></td>
		      		<td class="text-center"><?php echo $data->id_status_pemesanan == 0?"Pengerjaan":"Selesai";?></td>
		      		<td class="text-center"><?php echo $data->id_status_pembayaran == 0?"Belum":"Lunas";?></td>
		      	</tr>
		      <?php $no++; } 
		      	}?>
		    </tbody>
		</table>
		<!-- /#wrapper -->
		<div class="modal fade" id="lihat-detail-pesanan" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div style="background-color:#2196f3; color: white" class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Rincian Pesanan</h3>
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
</table>
		<?php echo $paging;?>
		</div>
	</div>
</body>
<script type="text/javascript">
    var detail=function(no_pesanan){
        var url='lihat_detail_pesanan/'+no_pesanan;
        var sendData=$.get(url);
        sendData.done(function(data){
            $("#detail-table").empty().append(data);
            $("#lihat-detail-pesanan").modal();
        });   
    }

</script>
<style type="text/css">
  caption.btn-toolbar.bottom {
    display: none;
  }
</style>
</html>