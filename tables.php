<?php
	include('./jsonql.php');
	@session_start();
	if (!isset($_SESSION["user"])) {
		header('location:./login.php');
	}
	$jsonui = new JsonUI("./");
	$dbs = $jsonui->getDBs();
	$_SESSION["db"] = $_GET['db'];
	$_SESSION["table"] = $_GET['table'];
?>
<html>
<head>
	<title>JsonQL | Tables</title>
	<link rel="stylesheet" href="./css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="./css/font-awesome.min.css">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="shortcut icon" href="logo.png">
	<link rel="stylesheet" href="./css/main.css">
	<link rel="stylesheet" href="./semantic/semantic.min.css">
</head>
<body style="overflow-x:hidden">
	<div class="nav">
		<table style="width:100%;height:100%">
			<tr>
				<td>
					<button class="widgets" id="slideout"><i class="fa fa-bars"></i></button>
				</td>
				<td style="text-align:right">
					<button class="widgets" onclick="location.href='config.php?logout=true'"><i class="fa fa-sign-out"></i></button>
				</td>
			</tr>
		</table>		
	</div>
	<div class="main">
		<br/>
		<h3>Table: <?php echo $_SESSION["table"]; ?></h3>
		<p align="right">
			<button class="widgets" id="insert_pop"><i class="fa fa-plus"></i></button>
		</p>
		<?php
			$jsonui->getRows($_SESSION["db"], $_SESSION["table"]);
		?>
	</div>
	<div class="side_bar">
		<center>
			<br/>
			<img src="logo.png" class="icon">
			<h5>[ user: <?php echo $_SESSION["user"]; ?> ]</h5>
			<style>
				a {
					color:black;
				}
			</style>
			<div class="side_tab">
				<div class="ui accordion">
				  <?php
				  	$cnt = 0;
				  	if (count($dbs) == 0) { ?>
				  		<b>Nothing to see Here...</b>
					<?php
				  	}
				  	else {
				  		foreach ($dbs as $k) {
				  			$cnt++;
				  			if ($cnt == 1) {?>
								<div class="active title">
								   <i class="dropdown icon"></i>
								  	<a href="./?db=<?php echo $k; ?>"><?php echo $k; ?></a>
							    </div>
							<?php
				  			}
				  			else { ?>
								<div class="title">
								   <i class="dropdown icon"></i>
								  	<a href="./?db=<?php echo $k; ?>"><?php echo $k; ?></a>
							    </div>
							<?php
				  			}
				  			?>
				  			<?php
				  				if ($cnt == 1) {?>
									<div class="active content">
							  			<?php
							  			$tabs = $jsonui->getTabs($k);
							  			foreach ($tabs as $t) {
							  				if ($cnt == 1) {?>
							  					<a href="./tables.php?db=<?php echo $k; ?>&table=<?php echo $t; ?>"><p><?php echo $t; ?></p></a>
											<?php
							  				}
							  				else {?>
												<a href="./tables.php?db=<?php echo $k; ?>&table=<?php echo $t; ?>"><p><?php echo $t; ?></p></a>
											<?php
							  				}
							  			}?>
						  			</div>
								<?php
				  				}
				  				else { ?>
									<div class="content">
							  			<?php
							  			$tabs = $jsonui->getTabs($k);
							  			foreach ($tabs as $t) {
							  				if ($cnt == 1) {?>
							  					<a href="./tables.php?db=<?php echo $k; ?>&table=<?php echo $t; ?>"><p><?php echo $t; ?></p></a>
											<?php
							  				}
							  				else {?>
												<a href="./tables.php?db=<?php echo $k; ?>&table=<?php echo $t; ?>"><p><?php echo $t; ?></p></a>
											<?php
							  				}
							  			}?>
						  			</div>
								<?php
				  			}
				  		}
				  	}

				  ?>
				</div>
			</div>
		</center>
	</div>
	<div class="ui modal scrolling">
	  <p align="right">
	  	<button id="close" class="widgets"><i class="fa fa-times"></i></button>
	  </p>
	  <h2>Table: <?php echo $_SESSION["table"]; ?></h2>
	  <h3>Insert New Row</h3>
	  <table style="border:1px solid lightgray;width:100%;background-color:#f2f2f2" border="1" cellpadding="4" cellspacing="0">
	  	<thead>
	  		<tr id="title_tab"></tr>
	  	</thead>
	  	<tbody>
	  		<tr id="insert_tab"></tr>
	  	</tbody>
	  </table>
	  <br/>
	  <br/>
	  <center>
	  	<button class="ui blue button" id="insert">Insert</button>
	  </center>
	</div>
	<script src="./js/jquery-3.3.1.js" type="text/javascript"></script>
	<script src="./js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="./js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
	<script src="./semantic/semantic.min.js" type="text/javascript"></script>
	<script src="./js/main.js" type="text/javascript"></script>
	<script>
		$('.ui.accordion')
		  .accordion()
		;
	</script>
	<script src="./js/get-tables.js" type="text/javascript"></script>
	<script src="./js/slider.js"></script>
</body>
</html>
