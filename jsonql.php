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
      //Read data of Files and decode them to get arrays
			$this->lock = json_decode(file_get_contents($dir."/security.json"), 1);
			$this->dbs = json_decode(file_get_contents($dir."/database.json"), 1);
			$this->users = json_decode(file_get_contents($dir."/users.json"), 1);
		}
	}	
?>
