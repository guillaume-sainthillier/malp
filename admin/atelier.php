<?php

require_once "../class/fen.class.php";

$f = new fen("Gestion ateliers",3);

$f->addJs("init_atelier();");
$f->displayHeader();

$tableau = "<br /><br /><table class=\"tab-tab\">
	<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
		<th>
			<a href =\"#ajouter\"><img src=\"../img/ajouter.png\"  title =\"Ajouter un atelier\" id=\"ajouter\" class=\"pointeur\" /></a>
		</th>
		<th>Nom</th>
		<th>Sujet</th>
		<th>Date création</th>
		<th>Date limite</th>
		<th>Créateur</th>
	</tr>";

	
$res = $f->query("SELECT atelier.nom as nom,sujet,deadline,dateCreation,idAtelier, u.login as nomUser
					FROM atelier, utilisateur u WHERE u.idUtilisateur = atelier.idUtilisateur 
					ORDER BY dateCreation DESC, atelier.idAtelier DESC;"); 


$odd = true;

if($res->numRows() == 0)
{
	$tableau .= "<tr class=\"tab-data ".($odd ? "odd" : "even")."\" >
					<td colspan=\"6\">Aucun atelier pour le moment</td>
				</tr>";
}
while($row = $res->fetch())
{	
	$tableau.="<tr class=\"tab-data ".($odd ? "odd" : "even")."\" >
					<td>
						<a href =\"#modifier\" onClick=\"return modifierAtelier(".$row["idAtelier"].");\" ><img src=\"../img/modifier.png\" alt=\"Modifier\" title=\"Modifier cet atelier\"/></a>
						<a href =\"#supprimer\" onClick=\"return supprimerAtelier(".$row["idAtelier"].");\"><img src=\"../img/supprimer.png\" alt=\"Supprimer\" title=\"Supprimer cet atelier\"/></a> 
					<td>".$row["nom"]."</td> 
					<td>".$row["sujet"]."</td>
					<td>".datetostring($row["dateCreation"])."</td>
					<td>".(datetostring($row["deadline"]) != "" ? datetostring($row["deadline"]) : "Aucune")."</td>
					<td>".s($row["nomUser"])."</td>
			   </tr>";
	$odd = !$odd;
}

$tableau .= "</table>";


// echo $tableau;

echo $tableau;


$f->displayFooter();
?>