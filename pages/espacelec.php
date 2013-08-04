<?php

require_once "../class/fen.class.php";

$f = new fen("Espace de lecture",0);

$f->addLibJs(array("jquery.pagination.js","jquery.commentaire.js","jquery.multiselect.min.js","jquery.multiselect.filter.min.js"));
$f->addLibCss(array("jquery.commentaire.css","jquery.multiselect.css"));



$pageDemandee 	= 1;
$nbParPage		= 2;
$nbArticles 	= 0;


if(isset($_POST["theme"]) and !in_array(0,$_POST["theme"]) and !in_array("0",$_POST["theme"]))
	$idThemes = array_map("e",$_POST["theme"]);
else
	$idThemes = array(0);
	
// var_dump($_POST);
if(isset($_POST["auteur"]) and !in_array(0,$_POST["auteur"]) and !in_array("0",$_POST["auteur"]))
	$idAuteurs = array_map("e",$_POST["auteur"]);
else
	$idAuteurs = array(0);
	
if(isset($_GET["atelier"]) and $_GET["atelier"]!=0)
	$idAtelier = array(e($_GET["atelier"]));
else
	if(isset($_POST["atelier"]) and !in_array(0,$_POST["atelier"]) and !in_array("0",$_POST["atelier"]))
		$idAtelier = array_map("e",$_POST["atelier"]);
	else
		$idAtelier = array(0);

$res = $f->query("SELECT COUNT(*) as nb
					FROM texte
					WHERE isPublic = '1' 
					AND isBrouillon = '0'
					AND isValide = '1'
					".( !in_array(0,$idThemes) ? "AND idTheme IN('".implode("','",$idThemes)."') ": "")."
					".( !in_array(0,$idAuteurs) ? "AND idUtilisateur IN('".implode("','",$idAuteurs)."') ": "")."
					".( !in_array(0,$idAtelier) ? "AND idAtelier IN('".implode("','",$idAtelier)."') ": "")."
					; ");
					
if($row = $res->fetch())
	$nbArticles = $row["nb"];

$maxPage		= ceil($nbArticles/$nbParPage);

if(isset($_GET["page"]))
	$pageDemandee = $_GET["page"];
	
if($pageDemandee < 1)
	$pageDemandee = 1;
elseif($pageDemandee > $maxPage)
	$pageDemandee = $maxPage;
	
$f->addJs("init_articles(".$pageDemandee.",".$nbParPage.",".$nbArticles.");");
$f->displayHeader();

					
$selectTheme = "<form method=\"POST\" action=\"\">
					Filtrer par thème: 
					<select id=\"theme\" name=\"theme[]\" multiple=\"multiple\">
						<option value=\"0\" ".(in_array(0,$idThemes) ? "selected=\"selected\"" : "").">Tous</option>";

$res = $f->query("SELECT idTheme, intitule
					FROM theme 
					ORDER BY intitule;");
while($row = $res->fetch())
	$selectTheme .= "<option value=\"".$row["idTheme"]."\" ".(in_array($row["idTheme"],$idThemes) ? "selected=\"selected\"" : "").">".ucfirst(s($row["intitule"]))."</option>";
$selectTheme .= "</select>
				
				<br />
				<br />
				Filtrer par auteur: 
				<select id=\"auteur\" name=\"auteur[]\"  multiple=\"multiple\">
					<option value=\"0\"  ".(in_array(0,$idAuteurs) ? "selected=\"selected\"" : "").">Tous</option>";

$res = $f->query("SELECT u.idUtilisateur as idUser, u.login as login
					FROM texte, utilisateur u
					WHERE u.idUtilisateur = texte.idUtilisateur
					GROUP BY 1;");
while($row = $res->fetch())
	$selectTheme .= "<option value=\"".$row["idUser"]."\"  ".(in_array($row["idUser"],$idAuteurs) ? "selected=\"selected\"" : "").">".ucfirst(s($row["login"]))."</option>";
$selectTheme .= "</select>";


$selectTheme .= "<br />
				<br />
				Filtrer par atelier: 
				<select id=\"atelier\" name=\"atelier[]\"  multiple=\"multiple\">
					<option value=\"0\"  ".(in_array(0,$idAtelier) ? "selected=\"selected\"" : "").">Tous</option>";
					
$res = $f->query("SELECT * from Atelier order by nom;");
while($row = $res->fetch())
	$selectTheme .= "<option value=\"".$row["idAtelier"]."\"  ".(in_array($row["idAtelier"],$idAtelier) ? "selected=\"selected\"" : "").">".ucfirst(s($row["nom"]))."</option>";
$selectTheme .= "</select>
				</form><br />";

echo $selectTheme .="<br /><br />
<div class=\"pagination\"></div>";

		
$f->displayFooter();
?>