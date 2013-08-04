<?php
	@session_start();
		
	if(!isset($_SESSION['admin']))
		$_SESSION['admin'] = 0;
	
	if(!isset($_SESSION['id']))
		$_SESSION['id'] = 0;
		
	if(!isset($_SESSION['login']))
		$_SESSION['login'] = "";
		
	if(!isset($_SESSION["lang"]))
		$_SESSION["lang"] = "en";
?>