<?php

	require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",0,"xml");

	if(isset($_POST["titre"]))
	{
		$idTexte = trim($_POST["titre"]);
		$texte = $f->query("Select contenu,titre,idTheme, isPublic from texte where idTexte=".e($idTexte).";");
		$row = $texte->fetch();
		if (!$row)
		{
			$f->ajaxErreur("","Le texte demandé est introuvable");
		} 
		$contenu = $row['contenu'];
		$titre = $row['titre'];
		$theme = $row['idTheme'];
		$privacy = $row['isPublic'];
		
		$f->retourAjax["contenu"] = $contenu;
		$f->retourAjax["titre"] = $titre;
		$f->retourAjax["theme"] = $theme;
		$f->retourAjax["privacy"] = $privacy;
		
		$f->ajaxOK("","Votre texte a bien été chargé");
		
	}
	
	$f->endAjax();
?>