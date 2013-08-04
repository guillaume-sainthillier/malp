<?php

require_once "../class/fen.class.php";


$f = new fen("Publiez vos textes",1);


$f->addLibJs(array("ckeditor/ckeditor.js","ckeditor/adapters/jquery.js"));
$f->addJs("init_publication();");
$f->displayHeader();

?>

<h1>Publiez vos textes</h1>
<br />
<br />
<br />


<h2> Charger un brouillon? </h2>
<br />
<br />
<?php
$req = $f->query("SELECT idTexte,titre 
					FROM texte 
					WHERE idUtilisateur = '".$_SESSION['id']."' 
					AND isBrouillon = 1;");
$titres = "<select name=\"titres\" id=\"titres\" class=\"left\"><option value=\"0\">Choisir titre</option>";
while($row2 = $req->fetch())
	$titres .= "<option value=\"".$row2["idTexte"]."\" >".s($row2["titre"])."</option>";
$titres .="</select>";
echo "<form method=\"POST\" action=\"".$_SERVER["PHP_SELF"]."\" onSubmit=\"return fill_texte();\">
<div class=\"left\">
<label id=\"lab\" for=\"titres\">Titre :</label> ".$titres."
<br />
<br />
<br />
<input type=\"submit\" value=\"Charger\" />
</div>

</form>"
?>
<br />
<br />
<br />
<br />



<h2>Publier un nouveau texte?</h2>

<?php

$res2 = $f->query("SELECT *
					FROM atelier WHERE idAtelier IN (SELECT idAtelier FROM participantsatelier WHERE idUtilisateur=".$_SESSION['id']."); ");
	
$res = $f->query("SELECT idTheme, intitule
					FROM theme ORDER BY intitule; ");

$ateliers=  "<select name=\"atelier\" id=\"atelier\" ><option value=\"0\">Choisir atelier</option>";
while($row2 = $res2->fetch())
	$ateliers .= "<option value=\"".$row2["idAtelier"]."\" >".s($row2["nom"])."</option>";
$ateliers.="</select>";

$themes = "<select name=\"theme\" id=\"theme\" ><option value=\"0\">Choisir thème</option>";
while($row = $res->fetch())
	$themes .= "<option value=\"".$row["idTheme"]."\" >".s($row["intitule"])."</option>";
$themes .="</select>";


echo "<form method=\"POST\" action=\"".$_SERVER["PHP_SELF"]."\" onSubmit=\"return send_texte();\">

<span id=\"feedback\"></span>
<br /> 
<br /> 

	<label for=\"titre\">Titre :</label> <input type=\"text\" id=\"titre\" name=\"titre\" value=\"\" /><br /><br />
	<label for=\"theme\">Thème :</label> ".$themes."<br/><br/>
	<label for=\"atelier\">Atelier :</label> ".$ateliers."
	<br /><br />
	<textarea id=\"texte\" name=\"texte\"></textarea>
	<br />
	<br />
	<div class=\"right\">
		<label for=\"brouillon\">Brouillon</label>
		<input type=\"checkbox\" name=\"brouillon\" id=\"brouillon\" /><br /><br /><br />";
	if($_SESSION["admin"] > 2)
	{
		echo "<label for=\"privacy\">Texte privé</label>
		<input type=\"checkbox\" name=\"privacy\" id=\"privacy\" />";
	}
	echo "
		
	</div>
	<br />
	<br />
	<br />
	<div class=\"center\">
		<input type=\"submit\" value=\"Publier\" />
	</div>
</form>";

	$f->displayFooter();
?>