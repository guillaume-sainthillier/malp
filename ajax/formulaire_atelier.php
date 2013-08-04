<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire atelier",3);

if(isset($_POST['mode']) and isset($_POST['idAtelier']))
{
	$mode			= $_POST['mode'];
	$idAtelier 		= $_POST['idAtelier'];
	
	if( !in_array($mode,array(1,2,3)))
		$f->ajaxErreur("Fonction non supportée","Ce mode n'est pas supporté pour ce module");
	
	
	$nom			 = "";
	$sujet			 = "";
	$deadline		 = "";
	$dateCreation	 = date("d/m/Y");
	$idUser 		 = $_SESSION["id"];
	
	if($mode > 1)
	{
		$res=$f->query("SELECT * 
						FROM atelier 
						WHERE idAtelier = '".e($_POST['idAtelier'])."';");
		if($row = $res->fetch())
		{
			$nom			 = $row['nom'];
			$sujet			 = $row['sujet'];
			$deadline		 = datetostring($row['deadline']);
			$dateCreation	 = datetostring($row['dateCreation']);
		}
	}
	
	

	$form = "<div id=\"feedback2\"></div><br />";
	$disable = "";
	if($mode == 1)
	{
		$boutton = "Ajouter";
		$header	 = "Ajout d'un atelier";
	}elseif($mode == 2)
	{
		$boutton = "Modifier";
		$header	 = "Modification d'un atelier";
	}else
	{
		$disable = "disabled=\"disabled\"";
		$boutton = "Supprimer";
		$header	 = "Suppression d'un atelier";
		$form 	.= BMInfo("La supression d'un atelier entraîne la suppression de tous les textes associés à cet atelier")."<br />";
	}
	
	$select = "<select name=\"user\" id=\"user\" $disable ><option value=\"0\">Aucun</option>";
	$res = $f->query("SELECT login, idUtilisateur 
						FROM utilisateur
						ORDER BY login;");

	while($row = $res->fetch())
		$select .= "<option value=\"".s($row["idUtilisateur"])."\" ".($row["idUtilisateur"] == $idUser ? "selected=\"selected\"": "").">".s($row["login"])."</option>";
	$select .= "</select>";
	
	
	
	
	$f->retourAjax["boutton"] = $boutton;
	
	$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionAtelier($(this));\">
	<label for=\"nom\">Nom: </label><input type=\"text\" name=\"nom\" value=\"".s($nom)."\" id=\"nom\" $disable /><br /><br />
	<label for=\"sujet\">Sujet: </label><textarea name=\"sujet\" id=\"sujet\" $disable >".s($sujet)."</textarea><br /><br />
	<label for=\"dateCreation\">Date création: </label><input class=\"datepicker\" type=\"text\" name=\"dateCreation\" value=\"".s($dateCreation)."\" id=\"dateCreation\" $disable /><br /><br />
	<label for=\"deadline\">Date limite: </label><input class=\"datepicker\" type=\"text\" name=\"deadline\" value=\"".s($deadline)."\" id=\"deadline\" $disable /><br /><br />
	<label for=\"user\">Créateur: </label>".$select."<br />
	<input type=\"hidden\" name=\"mode\" value=\"".$mode."\"/>
	<input type=\"hidden\" name=\"idAtelier\" value=\"".$idAtelier."\"/>
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}

$f->endAjax();
?>