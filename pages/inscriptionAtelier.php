<?php

require_once "../class/fen.class.php";

$f = new fen("Gestion ateliers",1);

$f->displayHeader();

$tableau = "<br /><span id=\"feedback\"></span><br /><br /><table class=\"tab-tab\">
	<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
		<th>
			
		</th>
		<th>Nom</th>
		<th>Sujet</th>
		<th>Date création</th>
		<th>Date limite</th>
		<th>Créateur</th>
	</tr>";

	
$res = $f->query("SELECT atelier.nom as nom,sujet,deadline,dateCreation,idAtelier, u.login as nomUser
					FROM atelier, utilisateur u 
					WHERE u.idUtilisateur = atelier.idUtilisateur 
					ORDER BY dateCreation DESC, atelier.idAtelier DESC;"); 

$req = $f->query("SELECT idAtelier 
					FROM participantsAtelier 
					WHERE idUtilisateur= '".$_SESSION['id']."' ;");
$tabAteliers = array();
while($row2 = $req->fetch())
	$tabAteliers[] = $row2["idAtelier"];
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
					<td id=\"action".$row["idAtelier"]."\">";
	
	if(!in_array($row["idAtelier"],$tabAteliers))
		$tableau.="<a href=\"#inscrire\" onClick=\"return inscriptionAtelier('".$row["idAtelier"]."');\" >
						<img src=\"../img/valider.png\" alt=\"S'inscrire\" title=\"S'inscrire à cet atelier\"/>
					</a>";
	else
		$tableau.="<a href=\"#desinscrire\" onClick=\"return desinscriptionAtelier('".$row["idAtelier"]."');\">
						<img src=\"../img/supprimer2.png\"   alt=\"Se désinscrire\" title=\"Se désinscrire de cet atelier\"/>
					</a>";

		$tableau.="</td><td>".$row["nom"]."</td> 
		<td>".$row["sujet"]."</td>
		<td>".datetostring($row["dateCreation"])."</td>
		<td>".datetostring($row["deadline"])."</td>
		<td>".s($row["nomUser"])."</td>
	</tr>";
	$odd = !$odd;
}

$tableau .= "</table>";


echo $tableau;


$f->displayFooter();
?>