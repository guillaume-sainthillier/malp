<?php
require_once "../class/fenajax.class.php";

	$f = new fenAjax("Commentaire",1);
	
	if(isset($_POST["commentaire"]) and isset($_POST["idTexte"]))
	{
		$res = $f->query("INSERT INTO retour(commentaire, isValide, datePoste, idUtilisateur, idTexte) 
							VALUES('".e($_POST["commentaire"])."','0',NOW(),'".$_SESSION["id"]."','".e($_POST["idTexte"])."');");
		$f->ajaxOK("","Votre commentaire est en cours de validation",false);
	}
	$f->endAjax();
?>