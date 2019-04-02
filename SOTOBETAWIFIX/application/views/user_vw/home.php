<?php
	defined('BASEPATH') or exit('No direct script allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Soto Betawi Ibu Hj. Titi Agus</title>
	<link rel="shortcut icon" href="<?php echo base_url('asset/ico/logoicon.ico'); ?>">
</head>
<body>
	<!-- carousel-->
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
	  <!-- Indicators -->
	  <ol class="carousel-indicators">
	    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	    <li data-target="#myCarousel" data-slide-to="1"></li>
	    <li data-target="#myCarousel" data-slide-to="2"></li>
	    <li data-target="#myCarousel" data-slide-to="3"></li>
	  </ol>

	  <!-- Wrapper for slides -->
	  <div class="carousel-inner" role="listbox">
	    <div class="item active">
	      <img class="img-responsive" src="asset/img/gmbr1.jpg" alt="1">
	      <div class="carousel-caption">
	        <h3>Mudah</h3>
	        <p>mulai dari 15 ribuan</p>
	      </div>
	    </div>

	    <div class="item">
	      <img class="img-responsive" src="asset/img/gmbr2.jpg" alt="2"> 
	      <div class="carousel-caption">
	        <h3>Senang</h3>
	        <p>Cita rasa yang khas</p>
	      </div>
	    </div>

	    <div class="item">
	      <center><img class="img-responsive" src="asset/img/gmbr3.jpg" alt="3"></center>
	      <div class="carousel-caption">
	        <h3>Nikmat</h3>
	        <p>cocok untuk penikmat santan</p>
	      </div>
	    </div>

	    <div class="item">
	      <img class="img-responsive" src="asset/img/gmbr4.jpg">
	      <div class="carousel-caption">
	        <h3>Higenis</h3>
	        <p>Lezat berkualitas</p>
	      </div>
	    </div>
	  </div>

	  <!-- Left and right controls -->
	  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>
    <!-- menu bar -->
	<div class="container">
		<div class="pull-left">
			<h3>Menu Soto</h3>
			<p>Pesan sekarang juga!.</p>	
		</div>
		<div style="padding-bottom:10px" class="pull-right">
			<a href="lihat-menu.html" class="btn btn-warning btn-sm btn-style">Lihat Semua</a>
		</div>
	</div>
	<div class="container">
		<!--menu contains-->
		<div id="content" class="row">
			<?php foreach ($food_menu as $food_menu): ?>
				  <div class="menu col-xs-6 col-sm-3">
				    <div class="thumbnail">
				      <img class="img-responsive" src="<?php echo $food_menu->picture;?>" style="height: 200px" alt="test">
				      <div  class="caption">
				        <h4 style="height:30px"><?php echo $food_menu->food_menu_name;?></h4>
				        <p class="ket" style="height:100px"><?php echo $food_menu->description;?></p>
				        <h5>Rp. <?php echo number_format($food_menu->price, 0, '','.').',-';?></h5>
				        <p class="text-center">
				        	<a href="<?php echo 'tambah-cart?id='.$food_menu->id_food_menu.'&nm='.$food_menu->food_menu_name.'&hrg='.$food_menu->price;?>" class="btn btn-warning btn-sm btn-style">
				        		<span class="glyphicon glyphicon-shopping-cart"></span> Pesan Sekarang
				        	</a>
				        </p>
				      </div>
				    </div>
				  </div>
			<?php endforeach;?>
		</div>
    </div>
</body>
<script type="text/javascript">
	$( document ).ready(function() {
    	if ($(window).width()<=320) {
    		$(".food_menu").toggleClass("col-xs-6");
    	}	
	});
</script>
</html>