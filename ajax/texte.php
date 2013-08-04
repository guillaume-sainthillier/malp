<?php

	require_once "../class/fenajax.class.php";
	
	$f = new fenAjax("Connexion",1);

	if(isset($_POST["texte"]) and isset($_POST["theme"]) and isset($_POST["titre"]) and $_POST["atelier"]==0 )
	{

		$titre = trim($_POST["titre"]);
		$theme = trim($_POST["theme"]);
		$contenu = trim($_POST["texte"]);
		$brouillon = $_POST["brouillon"];
		$privacy = $_POST["privacy"];
		if ($brouillon == "true")
		{
			if ($privacy == "true")
			{
				$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme, isBrouillon,isPublic)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',1,0);");
			}
			else
			{
				$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme, isBrouillon,isPublic)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',1,1);");
			}
			
		}
		else
		{
			
			$f->query("DELETE FROM texte where idUtilisateur ='".$_SESSION['id']."' and titre ='".e($titre)."';");
			if ($privacy == "true")
			{
				$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme, isBrouillon,isPublic)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',0,0);");
			}
			else
			{
				$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme, isBrouillon,isPublic)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',0,1);");
			}
			
		}
		
		
		$f->ajaxOK("","Votre texte a bien été enregistré");
		
	}
	elseif(isset($_POST["idTexte"]) && isset($_POST['titre']) && isset($_POST['contenuT']))
	{
		$res = $f->query("UPDATE texte SET titre='".e($_POST['titre'])."', contenu='".e($_POST['contenuT'])."' WHERE idTexte= '".e($_POST["idTexte"])."';");
		$f->ajaxOK("","Texte modifié",false);
	}
	
	if(isset($_POST["texte"]) and isset($_POST["theme"]) and isset($_POST["titre"])  and $_POST["atelier"]!=0 )
	{
		$titre = trim($_POST["titre"]);
		$theme = trim($_POST["theme"]);
		$contenu = trim($_POST["texte"]);
		$atelier = $_POST["atelier"];
		$brouillon = $_POST["brouillon"];
		if ($brouillon == "true")
		{
			$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme,idAtelier,isBrouillon)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',".$atelier.",1);");
		}
		else
		{
			$f->query("INSERT INTO texte (idUtilisateur,titre,contenu,idTheme,idAtelier, isBrouillon)
			VALUES ('".$_SESSION['id']."','".e($titre)."','".e($contenu)."','".e($theme)."',".$atelier.",0);");
		}
		$f->ajaxOK("","Votre texte a bien été enregistré");
		
	}
	$f->endAjax();
?>