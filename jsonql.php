<?php
	class JsonQL {
		public $lock;
		public $dbs;
		public $dir;
		public $user = "";
		public $password = "";
		public $database = "";
		public $users;
		function __construct($dir) {
			$this->dir = preg_replace("/\/$/","",$dir);
			$dir = $this->dir;
			$this->lock = json_decode(file_get_contents($dir."/security.json"), 1);
			$this->dbs = json_decode(file_get_contents($dir."/database.json"), 1);
			$this->users = json_decode(file_get_contents($dir."/users.json"), 1);
		}
		function createDB($db) {
			if (!in_array($db, $this->dbs)) {
				array_push($this->dbs, $db);
				$db_config = fopen($this->dir."/database.json", "w");
				fwrite($db_config, json_encode($this->dbs));
				fclose($db_config);

				if (!in_array("databases", scandir($this->dir))) {
					mkdir($this->dir."/databases");
				}

				$db_file = fopen($this->dir."/databases/".$db."."."json", "w");
				fwrite($db_file, json_encode(array()));
				fclose($db_file);

				array_push($this->lock, [$db => ["user" =>"root", "password"=>""]]);

				$db_sec = fopen($this->dir."/security.json", "w");
				fwrite($db_sec, json_encode($this->lock));
				fclose($db_sec);
				return true;
			}
			else {
				error_log("Invalid: <DB [".$db."] Already Exists>");
				return false;
			}
		}
		function connect($db, $user, $password) {
			$arr = array();
			$u = "";
			$p = "";
			foreach ($this->lock as $key=>$value) {
				foreach ($this->lock[$key] as $k=>$v) {
					if ($k == $db) {
						$u = $this->lock[$key][$k]["user"];
						$p = $this->lock[$key][$k]["password"];
						break 2;
					}
				}
			}
			if (($u == $user && $p == $password) || ($user == "root" && $password == "")) {
				$this->user = $u;
				$this->password = $p;
				$this->database = $db;
				return true;
			}
			else {
				error_log("AccessError: <Access Denied to user (".$user.") to DB (".$db.")>");
				return false;
			}
		}
		function bindUser($db, $user) {
			$u = "";
			$p = "";
			foreach ($this->users as $key=>$value) {
				foreach ($this->users[$key] as $k=>$v) {
					if ($k == $user) {
						$u = $k;
						$p = $this->users[$key][$k]["key"];
					}
				}
			}

			foreach ($this->lock as $key=>$value) {
				foreach ($this->lock[$key] as $k=>$v) {
					if ($k == $db) {
						$this->lock[$key][$k]["user"] = $u;
						$this->lock[$key][$k]["password"] = $p;

						$db_sec = fopen($this->dir."/security.json", "w");
						fwrite($db_sec, json_encode($this->lock));
						fclose($db_sec);

						break 2;
					}
				}
			}			
		}
		function createUser($user, $password) {
			$u = array();
			foreach ($this->users as $key=>$value) {
				foreach ($this->users[$key] as $k=>$v) {
					array_push($u, $k);
				}
			}
			if (!in_array($user, $u)) {
				array_push($this->users, [$user => ["key" => $password]]);

				$db_user = fopen($this->dir."/users.json", "w");
				fwrite($db_user, json_encode($this->users));
				fclose($db_user);
				return true;
			}
			else {
				error_log("FATAL: <User [".$user."] already exists>");
				return false;
			}
		}
		function query($db, $string) {
			$db_file = json_decode(file_get_contents($this->dir."/databases/".$db.".json"), 1);
			if ($this->user != "" && $this->database != "" && $db == $this->database) {
				if (stripos($string, "CREATE TABLE") === 0) {
					$u = array();
					$string = trim(str_ireplace("CREATE TABLE", "", $string));
					preg_match("/^(\w|\W)+.*\(/", $string, $t);
					$tabname = trim(str_replace("(", "", $t[0]));
					foreach ($db_file as $key=>$value) {
						foreach ($db_file[$key] as $k=>$v) {
							array_push($u, $k);							 
						}
					}
					if (!in_array($tabname, $u)) {
						
						preg_match("/\(.*\)/", $string, $c);
						$con = preg_replace("/^\(/", "",preg_replace("/\)$/", "", $c[0]));
						$con = preg_replace("/\,\s/", ",", $con);
						$items = array();
						if (preg_match("/\,/", $con)) {
							$items = explode(",", $con);
						}
						else {
							$items[0] = trim($con);
						}
						$data_types = array();
						$columns = array();
						foreach ($items as $i) {
						 	array_push($columns, [explode(" ", $i)[0] => []]);
						 	array_push($data_types, strtolower(explode(" ", $i)[1]));
						}
						$table = [$tabname => ["columns" => $columns, "data_types" => $data_types]];

						array_push($db_file, $table);

						$tabs = fopen($this->dir."/databases/".$db.".json", "w");
						fwrite($tabs, json_encode($db_file));
						fclose($tabs);
						return true;
					}
					else {
						error_log("Invalid: <Table [".$tabname."] already Exists>");
						return false;
					}
				}
				else if (stripos($string, "DROP TABLE") === 0) {
					$string = trim(str_ireplace("DROP TABLE ", "", $string));
					for ($i = 0;$i < count($db_file);$i++) {
						foreach ($db_file[$i] as $key=>$value) {
							if ($key == $string) {
								unset($db_file[$i]);
								array_values($db_file);
								$tabs = fopen($this->dir."/databases/".$db.".json", "w");
								fwrite($tabs, json_encode($db_file));
								fclose($tabs);
								break 2;
								return true;
							}
						}
					}
					return false;
				}
				else if (stripos($string, "SELECT FROM") === 0) {
					$string = trim(str_ireplace("SELECT FROM ", "", $string));
					$cols = array();
					$columns = array();
					$return_val = array();
					for ($i = 0;$i < count($db_file);$i++) {
						foreach ($db_file[$i] as $key=>$value) {
							if ($string == $key) {
								$cols = $db_file[$i][$key]["columns"];
								foreach ($cols as $k=>$v) {
									foreach ($cols[$k] as $in=>$an) {
										$columns[$in]= $an;
									}
								}
								return new Handler($columns);
								break 2;							
							}
						}
					}
					return null;
				}
			}
			else {
				error_log("Error: <No connection existent!>");
			}
		}
		function insert($db, $table, $values) {
			if ($this->user != "" && $this->database != "" && $db == $this->database) {
				$db_file = json_decode(file_get_contents($this->dir."/databases/".$db.".json"), 1);
				$cnt = -1;
				for ($i = 0;$i < count($db_file);$i++) {
					foreach ($db_file[$i] as $key=>$value) {
						if ($table == $key) {
							$cols = $db_file[$i][$key]["columns"];
							foreach ($cols as $k=>$v) {
								foreach ($cols[$k] as $in=>$an) {
									$cnt++;
									switch ($db_file[$i][$key]["data_types"][$cnt]) {
										case "number":
											if (!is_numeric($values[$cnt])) {
												goto a;
											}
										break;
										case "text":
											if (!is_string($values[$cnt])) {
												goto a;
											}
										break;
									}
									array_push($db_file[$i][$key]["columns"][$k][$in], trim(htmlspecialchars($values[$cnt])));
								}
							}
							$tabs = fopen($this->dir."/databases/".$db.".json", "w");
							fwrite($tabs, json_encode($db_file));
							fclose($tabs);
							break 2;
						}
					}
				}
			}
			else {
				error_log("Error: <No connection existent!>");	
			}
			return false;
			a:
			error_log("TypeError: <Invalid data type(s) provided>");
		}
		function update($db, $table, $x, $param) {
			if ($this->user != "" && $this->database != "" && $db == $this->database) {
				$db_file = json_decode(file_get_contents($this->dir."/databases/".$db.".json"), 1);
				$to_up = array();
				$where = "";
				$equal = "";
				foreach ($param as $key=>$value) {
					$where = $key;
					$equal = $value;
				}

				for ($i = 0;$i < count($db_file);$i++) {
					foreach ($db_file[$i] as $key=>$value) {
						if ($table == $key) {
							$cols = $db_file[$i][$key]["columns"];
							foreach ($cols as $k=>$v) {
								foreach ($cols[$k] as $in=>$an) {
									if ($in == $where) {
										foreach ($db_file[$i][$key]["columns"][$k][$in] as $l=>$m) {
											if ($m == $equal) {
												array_push($to_up, $l);
											}
											foreach ($x as $n=>$o) {
												try {
													foreach ($to_up as $p) {
														foreach ($db_file[$i][$key]["columns"] as $q=>$r) {
															foreach ($db_file[$i][$key]["columns"][$q] as $s=>$t) {
																if ($n == $s) {
																	$db_file[$i][$key]["columns"][$q][$n][$p] = $o;
																}
															}
														}
													}
												}
												catch (Exception $ex) {
													return false;
												}
											}
											$tabs = fopen($this->dir."/databases/".$db.".json", "w");
											fwrite($tabs, json_encode($db_file));
											fclose($tabs);
										}
										break 4;
									}
								}
							}
						}
					}
				}
				return false;
			}
			else {
				error_log("Error: <No connection existent!>");	
			}
		}
		function delete($db, $table, $param) {
			if ($this->user != "" && $this->database != "" && $db == $this->database) {
				$db_file = json_decode(file_get_contents($this->dir."/databases/".$db.".json"), 1);
				$to_up = array();
				$where = "";
				$equal = "";
				foreach ($param as $key=>$value) {
					$where = $key;
					$equal = $value;
				}

				for ($i = 0;$i < count($db_file);$i++) {
					foreach ($db_file[$i] as $key=>$value) {
						if ($table == $key) {
							$cols = $db_file[$i][$key]["columns"];
							foreach ($cols as $k=>$v) {
								foreach ($cols[$k] as $in=>$an) {
									if ($in == $where) {
										foreach ($db_file[$i][$key]["columns"][$k][$in] as $l=>$m) {
											if ($m == $equal) {
												array_push($to_up, $l);
											}
											try {
												foreach ($to_up as $p) {
													foreach ($db_file[$i][$key]["columns"] as $q=>$r) {
														foreach ($db_file[$i][$key]["columns"][$q] as $s=>$t) {
															unset($db_file[$i][$key]["columns"][$q][$s][$p]);
														}
													}
												}
											}
											catch (Exception $ex) {
												return false;
											}

											$tabs = fopen($this->dir."/databases/".$db.".json", "w");
											fwrite($tabs, json_encode($db_file));
											fclose($tabs);
										}
										break 4;
									}
								}
							}
						}
					}
				}
				return false;
			}
			else {
				error_log("Error: <No connection existent!>");	
			}
		}
		function deleteDB($db) {
			if ($this->user != "" && $this->database != "" && $db == $this->database) {
				$db_file = json_decode(file_get_contents($this->dir."/databases/".$db.".json"), 1);
				unlink($this->dir."/databases/".$db.".json");
				foreach ($this->lock as $k=>$v) {
					if ($k == $db) {
						unset($this->lock[$k]);
						array_values($this->lock);
						$tabs = fopen($this->dir."/security.json", "w");
						fwrite($tabs, json_encode($this->lock));
						fclose($tabs);
						break;
					}
				}
				foreach ($this->dbs as $k=>$v) {
					if ($k == $db) {
						unset($this->dbs[$k]);
						array_values($this->dbs);
						$tabs = fopen($this->dir."/database.json", "w");
						fwrite($tabs, json_encode($this->dbs));
						fclose($tabs);
						break;
					}
				}
			}
			else {
				error_log("Error: <No connection existent!>");	
			}
		}
	}
	function num_rows($x) {
		$cnt = 0;
		$key = "";
		foreach ($x as $k=>$v) {
			$cnt++;
			if ($cnt == 1) {
				$key = $k;
				break;
			}
		}
		return count($x[$key]);
	}
	class Handler {
		public $data;
		function __construct($data) {
			$this->data = $data;
		}
		function like($x, $y) {
			$arr = array();
			$return_array = array();
			switch ($y) {
				case 0:
					foreach ($x as $key=>$value) {
						foreach ($this->data as $k=>$v) {
							if ($key == $k) {
								foreach ($this->data[$k] as $l=>$m) {
									if (stripos(strval($m), strval($value)) === 0) {
										array_push($arr, $l);
									}
								}
							}
						}
					}
					foreach ($this->data as $key=>$value) {
						foreach ($arr as $k=>$v) {
							$return_array[$key][$v] = $this->data[$key][$v];
						}
					}
					foreach ($return_array as $k=>$v) {
						$return_array[$k] = array_values($return_array[$k]);
					}
					return $return_array;
				break;
				case 1:
					foreach ($x as $key=>$value) {
						foreach ($this->data as $k=>$v) {
							if ($key == $k) {
								foreach ($this->data[$k] as $l=>$m) {
									if (stripos(strval($m), strval($value)) !== false) {
										array_push($arr, $l);
									}
								}
							}
						}
					}
					foreach ($this->data as $key=>$value) {
						foreach ($arr as $k=>$v) {
							$return_array[$key][$v] = $this->data[$key][$v];
						}
					}
					foreach ($return_array as $k=>$v) {
						$return_array[$k] = array_values($return_array[$k]);
					}
					return $return_array;
				break;
				case -1:
					foreach ($x as $key=>$value) {
						foreach ($this->data as $k=>$v) {
							if ($key == $k) {
								foreach ($this->data[$k] as $l=>$m) {
									if (strripos(strval($m), strval($value), strlen($value) - 1) === (strlen($m) - strlen($value))) {
										array_push($arr, $l);
									}									
								}
							}
						}
					}
					foreach ($this->data as $key=>$value) {
						foreach ($arr as $k=>$v) {
							$return_array[$key][$v] = $this->data[$key][$v];
						}
					}
					foreach ($return_array as $k=>$v) {
						$return_array[$k] = array_values($return_array[$k]);
					}
					return $return_array;
				break;
				default:
					foreach ($x as $key=>$value) {
						foreach ($this->data as $k=>$v) {
							if ($key == $k) {
								foreach ($this->data[$k] as $l=>$m) {
									if (stripos(strval($m), strval($value)) !== false) {
										array_push($arr, $l);
									}
								}
							}
						}
					}
					foreach ($this->data as $key=>$value) {
						foreach ($arr as $k=>$v) {
							$return_array[$key][$v] = $this->data[$key][$v];
						}
					}
					foreach ($return_array as $k=>$v) {
						$return_array[$k] = array_values($return_array[$k]);
					}
					return $return_array;
				break;
			}
					
		}
		function equals($x) {
			$arr = array();
			$return_array = array();
			foreach ($x as $key=>$value) {
				foreach ($this->data as $k=>$v) {
					if ($key == $k) {
						foreach ($this->data[$k] as $l=>$m) {
							if ($m == $value) {
								array_push($arr, $l);
							}
						}
					}
				}
			}
			foreach ($this->data as $key=>$value) {
				foreach ($arr as $k=>$v) {
					$return_array[$key][$v] = $this->data[$key][$v];
				}
			}
			foreach ($return_array as $k=>$v) {
				$return_array[$k] = array_values($return_array[$k]);
			}
			return $return_array;
		}
		function all() {
			return $this->data;
		}
	}
	/*
	<Some usage examples are shown below>
		$json = new JsonQL("./");
		$json->createDB("workers");
		$json->connect("workers", "root", "");
		$json->createUser("me", "password");
		$json->bindUser("workers", "me");
		$json->query("workers", "CREATE TABLE home_alone(one number, two text, three date)");
		$json->query("workers", "DROP TABLE home_alone");
		print_r($json->query("workers", "SELECT FROM home_alone")->like(["two"=>"l"], -1));
		print_r($json->query("workers", "SELECT FROM home_alone")->equals(["one"=>50, "two"=>"Daniel"]));
		echo num_rows($q);
		$json->insert("workers", "home_alone", [50, 'Daniel', "21"]);
		$json->delete("workers", "home_alone", ["two"=>"Edinyanga"]);
	</Examples>
	*/
?>
