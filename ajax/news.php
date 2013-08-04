<?php

	require_once "../class/fenajax.class.php";


	if(isset($_POST["idNews"]) and isset($_POST["titre"]) and isset($_POST["contenuN"]))
	{
		$f = new fenAjax("Connexion",0);
		$res = $f->query("UPDATE news SET titreN='".e($_POST['titre'])."', contenuN='".e($_POST['contenuN'])."' WHERE idNews= '".e($_POST["idNews"])."';");
		$f->ajaxOK("","News modifié",false);
	}
	
	if(isset($_POST["titre2"]) and isset($_POST["contenuN2"]))
	{
		$f = new fenAjax("Connexion",0);
		$res = $f->query("INSERT INTO news (titreN,contenuN,idUtilisateur) VALUES ('".e($_POST['titre2'])."','".e($_POST['contenuN2'])."',".$_SESSION['id'].");");
		$f->ajaxOK("","News ajoutée",false);
	}
	$f->endAjax();
?>	