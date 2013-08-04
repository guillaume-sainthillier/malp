<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Articles",0);
	
$f->ajaxRetour["html"] = "";
if(isset($_POST["iPage"]) and isset($_POST['nb']))
{
	$iPage 		= $_POST["iPage"];
	$nb			= $_POST["nb"];
	$limiteDeb	= ($iPage-1) * $nb;
	$limiteFin	= $limiteDeb + $nb;
	
	$res = $f->query("SELECT phraseFinale,dateDebut,dateFin, u.login as login, idCadavre
						FROM cadavre, utilisateur u
						WHERE u.idUtilisateur = cadavre.idUtilisateur 
						AND isEnCours = '0' 
						ORDER BY dateDebut DESC
						LIMIT ".$limiteDeb.", ".$limiteFin.";");
	$html = $res->numRows() == 0 ? "Aucun résultat disponible" : "";
	while($row = $res->fetch())
	{
		$html .= "Créé par <b>".s($row["login"])."</b> le ".datetostring($row["dateDebut"],true).", terminé le ".datetostring($row["dateFin"],true)."<br />
				<br />
				Résultat du jeu:
				<br />
				<br />
				".$row["phraseFinale"]."
				<br />
				<br />
				<button onClick=\"details($(this));\">Détails</button>
				<div class=\"details\"><br /><ul>";
		
		
		$res2 = $f->query("SELECT phrase,u.login as login
							FROM phrase, utilisateur u
							WHERE idCadavre = '".$row["idCadavre"]."'
							AND phrase.idUtilisateur = u.idUtilisateur
							ORDER BY idPhrase ASC;");
		while($row2 = $res2->fetch())		
			$html .= "<li><b>".s($row2["login"])."</b>: ".s($row2["phrase"])."</li>";
		
		$html .= "</ul></div><br /><br />";
	}
	
	$f->ajaxOK("","");
	$f->retourAjax["html"] = $html;
}
$f->endAjax();
?>