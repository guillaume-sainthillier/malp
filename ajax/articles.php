<?php
require_once "../class/fenajax.class.php";

$f = new fenAjax("Articles",0);
	
$f->ajaxRetour["html"] = "";
if(isset($_POST["iPage"]) and isset($_POST['nb']))
{

	$iPage 		= $_POST["iPage"];
	if($iPage == 0)
		$iPage = 1;
	$nb			= $_POST["nb"];
	$limiteDeb	= ($iPage-1) * $nb;
	$limiteFin	= $nb;
	
	$idThemes = array();
	if(isset($_POST["theme"]) and !in_array("0",$_POST["theme"]))
		$idThemes = array_map("e",$_POST["theme"]);
	
	$idAuteurs = array();
	if(isset($_POST["auteur"]) and !in_array("0",$_POST["auteur"]))
		$idAuteurs = array_map("e",$_POST["auteur"]);
	
	$idAtelier = array();
	if(isset($_POST["atelier"]) and !in_array("0",$_POST["atelier"]))
		$idAtelier = array_map("e",$_POST["atelier"]);
		
	$res = $f->query("SELECT titre,contenu,dateAjout, u.login as login, idTexte, t.intitule as intitule
						FROM texte, utilisateur u, theme t
						WHERE u.idUtilisateur = texte.idUtilisateur 
						AND t.idTheme = texte.idTheme 
						AND isPublic = '1' 
						AND isBrouillon = '0'
						AND isValide = '1' 
						".( count($idThemes) > 0 ? "AND t.idTheme IN('".implode("','",$idThemes)."') ": "")."
						".( count($idAuteurs) > 0 ? "AND u.idUtilisateur IN('".implode("','",$idAuteurs)."') ": "")."
						".( count($idAtelier) > 0 ? "AND texte.idAtelier IN('".implode("','",$idAtelier)."') ": "")."
						ORDER BY dateAjout DESC
						LIMIT ".$limiteDeb.", ".$limiteFin.";");
	$html = $res->numRows() == 0 ? "Aucun article trouvé" : "";
	
	while($row = $res->fetch())
	{
		$html .= "<div class=\"articulo\"><h1>".s($row["titre"])."</h1><br /><br />
			<i>Par ".$row["login"].", dans la catégorie <b>".$row["intitule"]."</b> ajouté le ".datetostring($row["dateAjout"])."</i><br /><br />
			".nl2br($row["contenu"])."<br /><br />";
			
		$res2 = $f->query("SELECT commentaire, datePoste, u.login as login
							FROM retour, utilisateur u
							WHERE u.idUtilisateur = retour.idUtilisateur
							AND isValide = '1'
							AND idTexte = '".$row["idTexte"]."'
							ORDER BY datePoste DESC;");
		// <div commentaires>
			// <div commentaire>
				// <span pseudo></span>
				// <span date></span>
				// <div contenu></div>
			// </div>
		// </div>
		
		$commentaires = "<div class=\"commentaires\" id=\"".$row["idTexte"]."\">";
		while($row2 = $res2->fetch())
		{
			$commentaires .= "<div class=\"commentaire\">
									<span class=\"date\">Le ".datetostring($row2["datePoste"],true)."</span>
									<div class=\"pseudo\">".ucfirst($row2["login"])."</div>
									<div class=\"contenu\">".ucfirst($row2["commentaire"])."</div>
								</div>";
		}
		$commentaires .= "</div>";
		
		$html .= $commentaires;
		$html .="</div><br />";
	}
	
	$f->ajaxOK("","");
	$f->retourAjax["html"] = $html;
}
$f->endAjax();
?>