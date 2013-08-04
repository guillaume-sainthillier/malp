<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire atelier",3);
if(isset($_POST['idRetour']))
{
	$idT=$_POST['idRetour'];
	$res = $f->query("SELECT * FROM retour WHERE idRetour = ".$idT.";");
	$row = $res->fetch();
	$resU2 = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur=".$row['idUtilisateur'].";");
	$rowU2 = $resU2->fetch();
	$resA2 = $f->query("SELECT * FROM texte WHERE idTexte=".$row['idTexte'].";");
	$rowA2 = $resA2->fetch();
	$form = "<div id=\"feedback2\"></div><br />";
	$boutton = "Modifier";
	$header= "Modification d'un commentaire";
	
	$f->retourAjax["boutton"] = $boutton;
		$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionRetour($(this));\">
	<label for=\"titre\">Sur le texte :</label><input type=\"text\" name=\"titre\" value=\"".s($rowA2['titre'])."\" id=\"titre\" / disabled><br/><br/>
	<label for='comm'> Commentaire : </label><center><textarea  name='comm' id='comm' rows='10' cols='30'>".s($row['commentaire'])."</textarea></center><br/><br/> 
	<input type='hidden' name='idRetour' id='idRetour' value='".s($row['idRetour'])."'<br /><br />
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}

$f->endAjax();
?>
