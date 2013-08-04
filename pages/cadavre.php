<?php

require_once "../class/fen.class.php";

$f = new fen("Cadavre exquis",1);
$f->addJs("init_cadavre();");
$f->displayHeader();

echo "<br />".BMInfo("Bienvenue sur le jeu du Cadavre exquis !")."<br /><br />

<div class=\"center\">
	<span id=\"feedback\"></span><br />";

$res = $f->query("SELECT * FROM cadavre WHERE isEnCours = '1' ;");
if(! $row = $res->fetch())
{
	echo "Il n'y a aucun jeu en cours<br /><br />";
	if($_SESSION["admin"] > 1)
		echo "<button id=\"newCadavre\" >Créer un nouveau jeu</button>";
}else
{
	echo "Une session est en cours <br /><br />";
	if($_SESSION["admin"] > 1)
		echo "<a id=\"rejoindreCadavre\" href=\"../pages/ce.php?i=".$row["idCadavre"]."\" class=\"button\" title=\"Rejoindre la partie en cours\">Rejoindre</a>";
	else
		echo BMInfo("Vous devez être adhérent pour pouvoir jouer au jeu",false);
}

echo "</div>";
					
$f->displayFooter();
?>