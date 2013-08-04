<?php

require_once "../class/fen.class.php";

$f = new fen("Plan du site",0);

$f->displayHeader();

?>

<h1>Plan du site</h1>
<div id="sitemap">
	<ul>
		<li><a href="accueil.html" title="Revenir à l'accueil">Accueil</a></li>
		<li><a href="association.html" title="Voir plus d'informations sur l'association">Association</a></li>
		<li><a href="ataliersenfantsado.html" title="Consulter les ateliers enfants ados">Ateliers enfants ado</a></li>
		<li><a href="atelierstt.html" title="Consulter les ateliers tout publics">Ateliers tous publics</a></li>
		<li><a href="ataliersspe.html" title="Consulter les ateliers spécifiques">Ateliers spécifiques</a></li>
		<li><a href="contact.html" title="Accéder à la page de contacts">Contact</a></li>           
		<li><a href="adherents.html" title="Consulter les modalités pour devenir adhérent">Devenir adhérent</a></li>
		<li><a href="espacelec.html" title="Accéder à l'espace lecture">Espace lecture</a></li>
		<li><a href="formation.html" title="Consulter les formations">Formations</a></li>
		<li><a href="publiez.html" title="Accéder à la page de publication de vos textes">Publiez vos textes</a></li>
		<li><a href="travauxredactionnels.html" title="Consulter les travaux rédactionnels">Travaux rédactionnels</a></li>
	</ul>
</div>

<?php

	$f->displayFooter();
?>