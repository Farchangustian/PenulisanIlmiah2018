<head>
	<title>Kerajang</title>
</head>
<div style="margin-top:20px;margin-bottom:30px" class='container'>
	<h3>Keranjang Pesanan</h3><br>
	<div class="col-md-12 table-responsive">
		<?php echo $table;?>	
	</div>
	<div class="col-md-7">
		 <div class="alert alert-danger">
  catatan : untuk pembelian di bawah 10 item akan dilakukan sistem COD, harap lihat baik-baik saat memilih metode pengambilan.
		</div>
	</div>
	<div class="col-md-5">
	<!--metode pembayaran-->
		<form action="proses_pesanan.html" method="post">
		<div class="panel panel-default">
			<div class="panel-heading">
				<b>Pilih Metode Pembayaran</b>
			</div>
			<select id="metode_pembayaran" onchange="pilihMetode()" name="metodeBayar" class="form-control select2-allow-clear">
			<option value="kosong" selected >-- Pilih metode --</option>
				<option value="0">Transfer</option>
				<option value="1">Cash</option>
			</select>
		</div>
	<!--metode pengiriman-->
		<div class="panel panel-default">
			<div class="panel-heading">
				<b>Pilih Metode Pengiriman</b>
			</div>
			<select id="metode_pengambilan" onchange="pilihMetode()" name="metode_pengambilan" class="form-control select2-allow-clear">
				<option value="kosong" selected >-- Pilih metode --</option>
				<?php
				foreach($ambil_metode as $l){ ?>
                  <option value="<?php echo $l['id_metode_pengambilan']; ?>"><?php echo $l['metode_pengambilan'].'-'.$l['Area'].'-'.$l['biaya'] ?></option>
				  <?php } ?>
			</select>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<b>DETAIL PEMESANAN</b>
			</div>
			<div class="panel-body table-responsive">
				<table class="table">
					<tr>
						<td class="middle">Jumlah Items</td>
						<td class="middle">:</td>
						<td class="middle"><?php echo $itemcart;?></td>
					</tr>
					<tr>
						<td class="middle">Total Tagihan</td>
						<td class="middle">:</td>
						<td class="middle">Rp. <?php echo number_format($total_harga, 0, '','.').',-';?></td>
					</tr>
				</table>
			</div>
			<div class="panel-footer">
				<?php if ($itemcart != 0 && $itemcart >= 0) {?>
					<input id="checkout" style="display: none" type="submit" class="btn btn-sm btn-style btn-info" value="Check Keluar">
				<?php }?>
						
			</div>
		</div>
		</form>
	</div>


</div>
<script type="text/javascript">
	var active_button = function(){
		 var currentRow=$(this).closest("tr");
		 var btndis= currentRow.find("td:eq(4) > button:eq(0)");
		 btndis.removeClass("disabled");
	}

	function pilihMetode() {
		var metode_pembayaran = document.getElementById("metode_pembayaran");
		var metode_pengambilan = document.getElementById("metode_pengambilan");
		
		var buttonCheckout = document.getElementById("checkout")
	
		if (metode_pembayaran.value == "kosong" || metode_pengambilan.value == "kosong") {
			buttonCheckout.style.display = "none"
		} else {
			buttonCheckout.style.display = "block"
		}
	}
</script>