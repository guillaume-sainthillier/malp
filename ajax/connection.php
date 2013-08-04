<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",0,"xml");
	
	$reponse = "";
	if(isset($_POST["login"]) and isset($_POST["mdp"]))
	{
		$res = $f->query("SELECT * FROM utilisateur WHERE login = '".e($_POST["login"])."' AND mdp = '".e($_POST["mdp"])."' ;");
		if($row = $res->fetch())
		{
			$f->ajaxOK("","Connexion réussie",false);
			$_SESSION["admin"] = $row["rang"];
			$_SESSION["id"] = $row["idUtilisateur"];
			$_SESSION["login"] = $row["login"];
		}else
		{
			$f->ajaxErreur("Connexion refusée","La connexion a échoué",false);
		}
	}
	
	$f->endAjax();
?>