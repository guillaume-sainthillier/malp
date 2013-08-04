<?php

	require_once "../class/fen.class.php";

	$f = new fen("Déconnexion",0);
	session_destroy();
	
	header("Location: ../pages/");

?>