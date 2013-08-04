<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire atelier",1);

if( isset($_POST['idAtelier']))
{
	$idUtilisateur=$_SESSION['id'];
	$idAtelier = $_POST['idAtelier'];
	
	$req = $f->query("SELECT * FROM participantsAtelier where idAtelier=".e($idAtelier)." and idUtilisateur=".$idUtilisateur.";");
	
	if( $req->numRows()==0)
	{
		$f->query("INSERT INTO participantsAtelier (idAtelier,idUtilisateur)  values('".e($idAtelier)."','".$idUtilisateur."');");
		$f->retourAjax["atelier"] = $idAtelier;
		$f->ajaxOK("","Votre inscription a bien été enregistrée");
	}
	else
	{
		$f->ajaxErreur("","Vous êtes déjà enregistré pour cet atelier");
	}
}

$f->endAjax();
?>