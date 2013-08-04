<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire atelier",3);
if(isset($_POST['idTheme']))
{
	$idT=$_POST['idTheme'];
	$res = $f->query("SELECT * FROM Theme WHERE idTheme = ".$idT.";");
	$row = $res->fetch();
	$form = "<div id=\"feedback2\"></div><br />";
	$boutton = "Modifier";
	$header= "Modification d'un thème";
	
	$f->retourAjax["boutton"] = $boutton;
		$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionTheme($(this));\">
	<label for=\"nom\">Intitule: </label><input type=\"text\" name=\"intitule\" value=\"".s($row['intitule'])."\" id=\"intitule\" />
	<input type='hidden' name='idTheme' id='idTheme' value='".s($row['idTheme'])."'<br /><br />
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}

$f->endAjax();
?>
