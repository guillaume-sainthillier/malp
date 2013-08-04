<?php
require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",3);
	
	if(isset($_POST["idRetour"]) && isset($_POST['comm']))
	{
		$res = $f->query("UPDATE retour SET commentaire='".$_POST['comm']."' WHERE idRetour= '".e($_POST["idRetour"])."';");
		$f->ajaxOK("","Retour modifié",false);
	}
	$f->endAjax();
?>