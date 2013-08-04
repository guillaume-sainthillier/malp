<?php

require_once "../class/fen.class.php";

$f = new fen("Jeux d'écriture",1);

$res = $f->query("SELECT COUNT(*) as nb
					FROM cadavre
					WHERE isEnCours = '0'; ");
					
$nbCadavre = 0;
if($row = $res->fetch())
	$nbCadavre = $row["nb"];


$f->addLibJs("jquery.pagination.js");
$f->addJs("init_resultat_jeux(1,2,".$nbCadavre.");");
$f->displayHeader();

?>
<br />
<h1>À quel jeux voulez-vous jouer ? </h1>
<br /><br />
<a href="../pages/cadavre.php" class="button" title="Accéder au jeu du cadavre exquis">Cadavre exquis</a><br /><br /><br />
<a href="../pages/texteMelange.php" class="button" title="Accéder au jeu du texte mélangé">Poème dada</a><br />

<br /><br /><h1>Résultats des précédents jeux :</h1>
<br />
<br />
<br />
<h2>Cadavre exquis</h2>
<br />
<div id="cadavre"></div>
<br />
<br />

<?php

$f->displayFooter();
?>