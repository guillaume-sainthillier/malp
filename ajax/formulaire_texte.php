<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire atelier",3);
if(isset($_POST['idTexte']))
{
	$idT=$_POST['idTexte'];
	$res = $f->query("SELECT * FROM Texte WHERE idTexte = ".$idT.";");
	$row = $res->fetch();
	$form = "<div id=\"feedback2\"></div><br />";
	$boutton = "Modifier";
	$header= "Modification d'un texte";
	
	$f->retourAjax["boutton"] = $boutton;
		$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionTexte($(this));\">
	<label for=\"titre\">Titre: </label><input type=\"text\" name=\"titre\" value=\"".s($row['titre'])."\" id=\"titre\" /><br/><br/>
	<label for='contenuT'> Contenu : </label><center><textarea  name='contenuT' id='contenuT' rows='10' cols='30'>".s($row['contenu'])."</textarea></center><br/><br/> 
	<input type='hidden' name='idTexte' id='idTexte' value='".s($row['idTexte'])."'<br /><br />
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}

$f->endAjax();
?>
