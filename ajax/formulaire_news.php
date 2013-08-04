<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Formulaire news",3);
if(isset($_POST['idNews']))
{
	$idT=$_POST['idNews'];
	$res = $f->query("SELECT * FROM News WHERE idNews = ".$idT.";");
	$row = $res->fetch();
	$form = "<div id=\"feedback2\"></div><br />";
	$boutton = "Modifier";
	$header= "Modification de la news";
	
	$f->retourAjax["boutton"] = $boutton;
		$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionNews($(this));\">
	<label for=\"titre\">Titre: </label><input type=\"text\" name=\"titre\" value=\"".s($row['titreN'])."\" id=\"titre\" /><br/><br/>
	<label for='contenuN'> Contenu : </label><center><textarea  name='contenuN' id='contenuN' rows='10' cols='30'>".s($row['contenuN'])."</textarea></center><br/><br/> 
	<input type='hidden' name='idNews' id='idNews' value='".s($row['idNews'])."'<br /><br />
	</form>";
	
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $form;
}
else
{
	if(!isset($_POST['idNews2']))
	{
		$form = "<div id=\"feedback2\"></div><br />";
		$boutton = "Ajouter";
		$header= "Ajout d'une news";
		
		$f->retourAjax["boutton"] = $boutton;
			$form .= "<form action=\"#\" method=\"post\" onSubmit=\"return actionNews($(this));\">
		<label for=\"titre2\">Titre: </label><input type=\"text\" name=\"titre2\"  id=\"titre2\" /><br/><br/>
		<label for='contenuN2'> Contenu : </label><center><textarea  name='contenuN2' id='contenuN2' rows='10' cols='30'></textarea></center><br/><br/> 
		</form>";
		
		$f->ajaxOK($header,"");
		$f->retourAjax["msg"] = $form;
	}
	else
	{
		$header= "Suppression de la news";
		$res = $f->query("DELETE FROM news WHERE idNews= '".e($_POST["idNews2"])."';");
		$mes="News supprimée";
		$f->ajaxOK($header,"");
		$f->retourAjax["msg"] = $mes;
	}	
}	

$f->endAjax();
?>