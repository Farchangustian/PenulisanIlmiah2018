<!DOCTYPE html>
<html lang="en">
<head>
	<title>ADMINISTRATOR</title>
</head>
<body class="">
	<div class="container">
		<div id="login-admin" class="col-md-3">
			<h4 class="text-left text-uppercase">Login Admin</h4>
			<?php if(!empty($error)) echo $error;?>
		<form action="<?php echo base_url('login/auth');?>" method="post">
		  <div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
					<input type="text" id="username" name="username" class="form-control" placeholder="Username" autofocus required>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-eye-close"></span></span>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
				</div>	
			</div>
			<button type="submit" name="submit" class="btn btn-default btn-block">Masuk</button>
		</form>
		</div>
	</div>
</body>
</html>