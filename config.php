<?php
	include("./jsonql.php");
	$jsonui = new JsonUI("./");
	@session_start();
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['values']) && isset($_POST["index"])) {
			try {
				$jsonui->updateRow($_POST['values'], $_POST['index']);
			}
			catch (Exception $ex) {
				echo "error";
			}
		}
		else if (isset($_POST["user"]) && isset($_POST["password"])) {
			try {
				if ($jsonui->login($_POST["user"], $_POST['password'])) {
					header("location:./");
				}
				else {
					header("location:./login.php");
				}
			}
			catch (Exception $ex) {
				header("location:./login.php");
			}	
		}
		else if (isset($_POST["index"]) && !isset($_POST["values"])) {
			try {
				$jsonui->deleteRow($_POST['index']);
			}
			catch (Exception $ex) {
				echo "error";
			}	
		}
	}
	else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if (isset($_GET["logout"])) {
			unset($_SESSION['dbs']);
			unset($_SESSION['user']);
			unset($_SESSION['password']);
			header("location:./login.php");
		}
	}
	else {
		echo "error";
		header("location:./");
	}
	//$jsonui->login("root", "");
	//$jsonui->deleteRow(0);
	//$jsonui->updateRow([], 0);
	//$jsonui->getRows("workers", "home_alone");
	//$jsonui->getDBs();
	//$jsonui->getTabs();
	//$jsonui->getRows("workers", "home_alone");
?>