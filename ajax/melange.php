<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",0,"xml");

	if(isset($_POST["texteOriginal"]) and isset($_POST["texteMelange"]) )
	{
		
		$original = trim($_POST["texteOriginal"]);
		
		$melange = trim($_POST["texteMelange"]);

			$f->query("INSERT INTO textemelange (texteOriginal,texteFinal,idUtilisateur)
			VALUES ('".e($original)."','".e($melange)."',".$_SESSION['id'].");");
		
		
		$f->ajaxOK("","Votre texte a bien été enregistré");
		
	}
	
	$f->endAjax();
?>