<?php

require_once "../class/fen.class.php";

$f = new fen("Publiez vos textes",0);

$f->displayHeader();

?>

<h1>L'idée</h1>
<br /><br />
<p>
	Vous aimez prendre votre plume ? Vous voulez partager vos créations ? Les Mots à La Pelle vous offrent un espace libre pour publier vos textes en ligne. L'espace de lecture (lien vers la page) est ouvert à tout contributeur inscrit. L'inscription est gratuite, et la publication de vos texte très simple.  <br /><br />
</p>
<h1>Le principe</h1>
<br /><br />
<p> 
	Il vous suffit de vous inscrire sur le site (inscription gratuite) et de publier votre texte.
	Votre texte apparaitra dans la zone « espace de lecture », avec votre pseudo. <br /><br />
</p>
<h1>Les règles</h1>
<br /><br />
<p> 
	Les textes sont soumis à validation du modérateur du site avant publication. <br />
	D'une part pour d'éventuelles coquilles ou corrections orthographiques, d'autre part pour garantir un contenu éthique. <br />
	Aucune correction ou réécriture d'ordre littéraire et subjective ne sera apportée aux textes qui restent la propriété de leurs auteurs.<br /><br />
	>> Inscrivez-vous comme contributeur
</p>

<?php

	$f->displayFooter();
?>