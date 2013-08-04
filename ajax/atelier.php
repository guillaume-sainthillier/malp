<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Gestion atelier",4);

if(isset($_POST['mode']) and isset($_POST['nom']) and isset($_POST['sujet'])
	and isset($_POST["dateCreation"]) and isset($_POST["deadline"]) and isset($_POST["user"]))
{
	$mode			= trim($_POST['mode']);
	$nom 			= trim($_POST['nom']);
	$sujet		 	= trim($_POST['sujet']);
	$dateCreation 	= trim($_POST['dateCreation']);
	$deadline 		= trim($_POST['deadline']);
	$user 			= trim($_POST['user']);
	if(isset($_POST['idAtelier']))
		$idAtelier = trim($_POST['idAtelier']);
	
	if( !in_array($mode,array(1,2,3)))
		$f->ajaxErreur("Fonction non supportée","Ce mode n'est pas supporté pour ce module");
	
	if($nom == "" or $sujet == "")
		$f->ajaxErreur("Champs obligatoire","Le ".($nom == "" ? "nom": "sujet")." ne doit pas être vide");
	
	
	if($mode == 1)
	{
		$f->query("INSERT INTO atelier(nom,sujet,deadline,dateCreation,idUtilisateur)
					VALUES('".e($nom)."','".e($sujet)."','".e(stringtodate($deadline))."',".($dateCreation == date("d/m/Y") ? "NOW()": "'".e(stringtodate($dateCreation))."'").", '".e($user)."');");
					
		$nomAction = "L'ajout de l'atelier <b>".s($nom)."</b> s'est effectué avec succès";
	}elseif($mode == 2)
	{
		$f->query("UPDATE ATELIER set nom='".e($nom)."', sujet='".e($sujet)."', dateCreation = ".($dateCreation == date("d/m/Y") ? "NOW()": "'".e(stringtodate($dateCreation))."'").", deadline='".e(stringtodate($deadline))."',idUtilisateur='".e($user)."' where idAtelier='".e($idAtelier)."';");
		$nomAction = "La modification de l'atelier <b>".s($nom)."</b> s'est effectuée avec succès";
	}else
	{
		$f->query("DELETE FROM texte where idAtelier='".e($idAtelier)."';");
		$f->query("DELETE FROM Atelier where idAtelier='".e($idAtelier)."';");
		$nomAction = "La suppression des textes et de l'atelier <b>".s($nom)."</b> s'est effectuée avec succès";
	}
		
	$f->retourAjax["mode"] = $mode;
	$f->ajaxOK("Action effectuée",$nomAction,false);
		
}

$f->endAjax();
?>