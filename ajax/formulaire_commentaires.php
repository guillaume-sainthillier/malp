<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire commentaire",3);
if(isset($_POST['idTexte']))
{
	$form = "<div id=\"feedback2\"></div><br />";
	$boutton="Commentez";
	$header="Commenter un texte";
	$f->retourAjax["boutton"] = $boutton;
		$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionCommentaire($(this));\">
	<label for='commentaire'> Commentaire : </label><center><textarea  name='commentaire' id='commentaire' rows='10' cols='30'/></center><br/><br/> 
	<input type='hidden' name='idTexte' id='idTexte' value='".s($_POST['idTexte'])."'<br /><br />
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}

$f->endAjax();
?>
