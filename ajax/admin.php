<?php
require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",3,"xml");
	
	$f->retourAjax["reload"] = true;
	if(isset($_POST["idUtilisateur"]) && isset($_POST['rank']))
	{
		$res = $f->query("SELECT * FROM Utilisateur WHERE idUtilisateur=".e($_POST["idUtilisateur"]).";");
		if(!$row = $res->fetch())
			$f->ajaxErreur("","Utilisateur introuvable");
			
		$newrang=$_POST['rank'];
		$f->query("UPDATE utilisateur SET rang= '".e($newrang)."' WHERE idUtilisateur= '".e($_POST["idUtilisateur"])."' ;");
		$f->ajaxOK("","Utilisateur modifié",false);
		$f->retourAjax["reload"] = false;

	}
	
	if(isset($_POST["titreT"]) && isset($_POST['contenuT']) && isset($_POST['idTexteM']))
	{
		$f->query("UPDATE texte SET titre='".e($_POST["titreT"])."',contenu='".e($_POST['contenuT'])."' WHERE idTexte= '".e($_POST['idTexteM'])."';");
		$f->ajaxOK("","Texte modifié",false);
	}
	
	if(isset($_POST["idUtilisateur2"]))
	{
		$res = $f->query("DELETE FROM utilisateur WHERE idUtilisateur= '".e($_POST["idUtilisateur2"])."' ;");
		$f->ajaxOK("","Utilisateur supprimé",false);

	}

	if(isset($_POST["idCadavre"]))
	{
		$res = $f->query("DELETE FROM cadavre WHERE idCadavre= '".e($_POST["idCadavre"])."' ;");
		$f->ajaxOK("","Cadavre supprimé",false);
	}
	
	if(isset($_POST["idTexte"]))
	{
		$res = $f->query("UPDATE texte SET isValide=1 WHERE idTexte= '".e($_POST["idTexte"])."';");
		$f->ajaxOK("","Texte validé",false);
	}
	
	if(isset($_POST["idTexte2"]))
	{
		$res = $f->query("DELETE FROM texte WHERE idTexte= '".e($_POST["idTexte2"])."';");
		$f->ajaxOK("","Texte supprimé",false);
	}
	
	if(isset($_POST["idRetourM"]) && isset($_POST["retour"]))
	{
		$res = $f->query("UPDATE retour  SET commentaire='".e($_POST["retour"])."' WHERE idRetour= '".e($_POST["idRetourM"])."';");
		$f->ajaxOK("","Commentaire modifié",false);
	}
	
	if(isset($_POST["idRetour"]))
	{
		$res = $f->query("UPDATE retour SET isValide=1 WHERE idRetour= '".e($_POST["idRetour"])."';");
		$f->ajaxOK("","Commentaire validé",false);
	}
	
	if(isset($_POST["idRetour2"]))
	{
		$res = $f->query("DELETE FROM retour WHERE idRetour=".e($_POST["idRetour2"]).";");
		$f->ajaxOK("","Commentaire supprimé",false);
	}	
	
	if(isset($_POST["idTheme2"]))
	{
		$res2= $f->query("DELETE FROM texte WHERE idTheme=".e($_POST["idTheme2"]).";");
		$res = $f->query("DELETE FROM theme WHERE idTheme=".e($_POST["idTheme2"]).";");
		$f->ajaxOK("","Thème supprimé",false);
	}
	$f->endAjax();
?>