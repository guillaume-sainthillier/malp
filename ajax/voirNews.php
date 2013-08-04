<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Affichage News",0);
if(isset($_POST['idNews']))
{
	$res = $f->query("SELECT * FROM news WHERE idNews = ".e($_POST['idNews']).";");
	$row = $res->fetch();
	$resU = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur = ".$row['idUtilisateur'].";");
	$rowU = $resU->fetch();
	$header= $row['titreN']." - News de : ".$rowU['nom'];
	$mes= "<p><center>".$row['contenuN']."</center></p>";
	$f->ajaxOK($header,"");
	$f->retourAjax["msg"] = $mes;
}
$f->endAjax();
?>