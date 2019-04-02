<head>
	<title>Proses Pesanan</title>
</head>
<div style="margin-top:20px;margin-bottom:30px" class="container">
	<h3>Proses Pemesanan</h3>
	<br>
	<div class="col-md-12">
		<?php if(isset($error)) echo $error;?>
	</div>
	<!--formnya-->
		<form id="invoice" action="prosesed.html" class="form-horizontal" method="POST" target="_blank">
			<div class="col-md-3">
				<img style="width:128px;height:128px;border:1px solid #D3D3D3;" class="img-responsive" src="<?php echo $this->session->userdata('profile_picture');?>">
				<br>
			</div>
			<div class="col-md-9">
				  <div class="form-group">
				    <label class="label-left col-sm-offset-1 col-sm-3">Nama Asli</label>
				    <div class="col-sm-8"> 
				      <input type="text" id="name" name="name" class="form-control"  value="<?php if(isset($invoice)) echo $invoice{0}->name;?>">
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="label-left col-sm-offset-1 col-sm-3">Alamat Rumah (Pengiriman)</label>
				    <div class="col-sm-8"> 
				      <textarea style="height:100px" type="text" id="address" name="address" class="form-control"><?php if(isset($invoice)) echo $invoice{0}->address;?></textarea>
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="label-left col-sm-offset-1 col-sm-3">Kode POS</label>
				    <div class="col-sm-8"> 
				      <input type="text" id="postal_zip" name="postal_zip" class="form-control"  value="<?php if(isset($invoice)) echo $invoice{0}->postal_zip;?>">
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="label-left col-sm-offset-1 col-sm-3">Tanggal Pengambilan</label>
				    <div class="col-sm-8"> 
				      <!-- Jika tanggal pesan minimal 3 hari dari sebelum pemesanan. <input type="date" min="<?= date('Y-m-d', strtotime('+3 days')) ?>" id="tgl_ambil" name="tgl_ambil" class="form-control"> -->
					  <!-- Jika tanggal pesan minimal 2 hari dari sebelum pemesanan. <input type="date" min="<?= date('Y-m-d', strtotime('+2 days')) ?>" id="tgl_ambil" name="tgl_ambil" class="form-control"> -->		
				      <input type="date" min="<?= date('Y-m-d', strtotime('+3 days')) ?>" id="tgl_ambil" name="tgl_ambil" class="form-control" required>
				      <input type="text" id="tgl_pesan" name="tgl_pesan" class="hidden form-control" required>
							<!-- itu tanggalnya error pas update . jadinya bulan 7 bukan bulan 8-->
				    </div>
				      
				  </div>
				  <div class="form-group">
				    <label class="label-left col-sm-offset-1 col-sm-3">Metode Pembayaran</label>
						<div class="col-sm-8">
							<div class="form-control"	>
								<?php
									echo ucfirst($metodeBayar);

									echo " || ";

									if ($metodeKirim == '20000') {
										echo 'Jakarta';
									}else if ($metodeKirim == '30000') {
										echo 'Depok';
									} else if ($metodeKirim == '25000') {
										echo 'Bekasi';
									} else if ($metodeKirim == '35000') {
										echo 'Bogor';
									}
									echo ' Rp. ';
									echo $metodeKirim;
								?>
								<input type="hidden" name="metode_pengambilan" value="<?php echo $metodeKirimId ?>">
								<input type="hidden" name="metodeBayar" value="<?php echo $metodeBayarId ?>">
								<input type="hidden" name="hargaMetode" value="<?php echo $metodeKirim ?>">
								
							</div>
							<!-- ini ketika di tampilkan ada. tapi pas di insert ga kehitung -->
						<!-- notifikasi jika  COD -->
						<div class="alert alert-danger alert-dismissible fade in" role="alert" id="alertnya" style="display:none;">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
									catatan : untuk sistem COD . kami akan menghubungi nomer anda. Jangan transfer dahulu sebelum kami hubungi anda. 
						</div>
						<!--- notifikasi jika  COD -->
						<!-- <select id="select2" onchange="pilihMetode()" name="metode" class="form-control select2-allow-clear">
								<option value="0">Ambil Sendiri</option>
								<optgroup label="Antar Soto">
								    <option value="20000">AREA 0.000)</option>
								    <option value="30000">AREA DEPOK (Rp. 30.000)</option>
								    <option value="25000">AREA BEKASI (Rp. 25.000)</option>
									<option value="35000">AREA BOGOR (Rp.35.000)</option>
									</optgroup>	
									<option value="cod">COD</option>
							</select> -->
					    </div>
				     </div>
				  </div>
			</div>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<b>DETAIL PEMESANAN</b>
			</div>
			<div class="panel-body table-responsive">
				<table class="table">
					<tr>
						<td class="middle">Nama Menu</td>
						<td class="middle">:</td>
						<td class="middle">
							<?php
								foreach ($this->cart->contents() as $items) {
									echo $items['name']."  x  ".$items['qty']." = Rp. ".number_format($items['qty']*$items['price'], 0, '','.').',-';
								} 
							?>
							<!--tampilkan harga ongkir-->
							+ <strong>
								<?php echo 'Rp. ' . number_format($metodeKirim) ?>,-
							</strong>
						</td>
					</tr>
					<tr>
						<td class="middle">Total Items</td>
						<td class="middle">:</td>
						<td class="middle"><?php echo $itemcart;?></td>
					</tr>
					<tr>
						<td class="middle">Total Tagihan</td>
						<td class="middle">:</td>
						<td class="middle">
							Rp. <?php echo number_format($total_harga + $metodeKirim, 0, '','.').',-';?>
							<span id="hargaMetode"></span>							
						</td>
					</tr>
				</table>
			</div>
			<div class="panel-footer text-right">
				<input type="submit" class="btn btn-sm btn-style btn-success" value="Proses Pemesanan">			
			</div>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript">
	function pilihMetode() {
		var harga = document.getElementById("select2").value;
		if (harga != 'cod') {
			if (harga != '0') {
			    document.getElementById("inputMetode").value = harga;	
			    document.getElementById("hargaMetode").innerHTML = "<b> + Rp. " + harga + "</b>";		
			} else if (harga) {
			    document.getElementById("inputMetode").value = 0;	
			    document.getElementById("hargaMetode").innerHTML = "";	
			}
		} else {
		    document.getElementById("hargaMetode").innerHTML = "";	
		}
	}

	$('#tgl_pesan').ready(function(){
		var rawdate= new Date();
		var date=rawdate.getFullYear()+"-"+rawdate.getMonth()+"-"+rawdate.getDate();
		var time=rawdate.getHours()+":"+rawdate.getMinutes()+":"+rawdate.getSeconds();
		var datetime=date+" "+time;
		$("#tgl_pesan").val(datetime);
	});
</script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<script type="text/javascript">
    $("select[name=metode]").change(function()
    {
		var harga = document.getElementById("select2").value;

    	if (harga == 'cod') {
	        $("#alertnya").show();
    	}
    });
</script>