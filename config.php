<?php
	include("./jsonql.php");
	$jsonui = new JsonUI("./");
	@session_start();
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['values']) && isset($_POST["index"])) {
			try {
				$n = array();
				if (strpos($_POST["values"], ',') !== false) {
					$n = explode(",", $_POST["values"]);
				}
				else {
					array_push($n, $_POST["values"]);
				}
				$jsonui->updateRow($n, $_POST['index']);
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
					header("location:./login.php?error=true");
				}
			}
			catch (Exception $ex) {
				header("location:./login.php?error=true");
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
		else if (isset($_POST["insert"])) {
			try {
				$n = array();
				if (strpos($_POST["insert"], ',') !== false) {
					$n = explode(",", $_POST["insert"]);
				}
				else {
					array_push($n, $_POST["insert"]);
				}
				$jsonui->insertRow($n);
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
	//$jsonui->insertRow("", []);
?>
