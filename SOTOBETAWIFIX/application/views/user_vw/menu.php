<?php
	defined('BASEPATH') or exit('No direct script allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Menu Soto</title>
</head>
<body style="padding-top:70px">
	<div class="container">
		<div class="pull-left">
			<h3>Menu Soto</h3>
			<p>Tersedia berbagai macam pilihan nikmat yang menanti anda!</p>	
		</div>
	</div>
	<div class="container">
		<div id="menulist" class="row">
		  	<?php foreach ($food_menu as $food_menu): ?>
				  <div class="menu col-xs-6 col-sm-3">
				    <div class="thumbnail">
				      <img class="img-responsive" src="<?php echo $food_menu->picture; ?>" style="height: 200px" alt="test">
				      <div class="caption">
				        <h4 style="height:30px"><?php echo $food_menu->food_menu_name;?></h4>
				        <p class="ket" style="height:100px"><?php echo $food_menu->description;?></p>
				        <h5>Rp. <?php echo number_format($food_menu->price, 0, '','.').',-';?></h5>
				        <p class="text-center">
				        	<a href="<?php echo "tambah-cart?id=".$food_menu->id_food_menu."&nm=".$food_menu->food_menu_name."&hrg=".$food_menu->price; ?>" class="btn btn-warning btn-sm btn-style">
				        		<span class="glyphicon glyphicon-shopping-cart"></span> Pesan Sekarang
				        	</a>
				        </p>
				      </div>
				    </div>
				  </div>
			<?php endforeach;?>
		</div>
		<div class="col-sm-offset-4 col-sm-4 col-sm-offset-4 text-center">
			<button id="loadmore" class="btn btn-info btn-sm btn-style"><img id="loader" src="asset/img/ajax-loader.gif"> Load More</button>
		</div>
	</div>	
</body>
<script type="text/javascript">
	$( document ).ready(function() {
		var count="<?php echo $count;?>";
		count=Number(count);
		$('#loader').hide();
    	if ($(window).width()<=320) {
    		$(".food_menu").toggleClass("col-xs-6");
    	}
    	$( "#loadmore" ).click(function() {
    		$("#loadmore").attr('disabled',true);
    		//change this to load more limit
    		$('#loader').show();
    		$.ajax({url: "user_cons/user/load_more/"+count+"/12", success: function(result){
    			$('#loader').hide();
            	$("#menulist").append(result);
            	$("#loadmore").attr('disabled',false);
            	if ($(window).width()<=320) {
    				$(".menu").removeClass("col-xs-6");
    			}
        	}});
        	//change this to load more offset
        	count=count+12;
    	});
	});
</script>
</html>
<br>