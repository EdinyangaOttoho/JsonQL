<html>
<head>
	<title>JsonQL | Login</title>
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/login.css">
	<link rel="shortcut icon" href="logo.png">
	<link rel="stylesheet" href="./semantic/semantic.min.css">
	<link rel="stylesheet" href="./css/main.css">
</head>
<body>
	<?php
		$warning = "";
		if (isset($_GET['error'])) {
			$warning = " warning";
		}
		else {
			$warning = "";
		}
	?>
	<center>
		<form class="center_modal" action="config.php" method="POST">
			<br/>
			<img src="logo.png" class="icon">
			<br/>
			<br/>
			<b>Welcome to JsonQL</b>
			<br/>
			<br/>
			<div class="ui form<?php echo $warning; ?>">
			  <div class="ui warning message">
			    Unable to Login. Access Denied
			  </div>
			  <div class="field">
			    <label align="left">E-mail</label>
			    <input type="text" name="user" placeholder="root" required>
			  </div>
			  <div class="field">
			    <label align="left">Password</label>
			    <input type="password" name="password">
			  </div>
			  <br/>
			  <center><button class="ui blue submit button" type="submit">Login</button></center>
			  <br/>
			  <br/>
			</div>
			<script src="./js/jquery-3.3.1.js" type="text/javascript"></script>
			<script src="./semantic/semantic.min.js" type="text/javascript"></script>
		</form>	
	</center>
</body>
</html>