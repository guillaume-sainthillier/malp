<?php

require_once "../class/fen.class.php";

$f = new fen("Administration",3);

$f->addLibJs("jquery.dataTables.min.js");
$f->addLibCss("jquery.dataTables_themeroller.css");
$f->addJs("init_admin();");
$f->displayHeader();


echo "<br />";
$resR = $f->query("SELECT * FROM utilisateur
					WHERE idUtilisateur=".$_SESSION['id'].";");
$rowR= $resR->fetch();
$rang=$rowR['rang'];
$res = $f->query("SELECT * FROM utilisateur
					ORDER BY login ;");
if($res->numRows() == 0)
{
	echo "Aucun utilisateur inscrit<br/><br/>";
}
else
{
	echo  "<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Liste de l'ensemble de l'association</caption>
	<thead>
	<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
		<th>Pseudo</th>
		<th>Nom</th>
		<th>Prénom</th>
		<th>Voir</th>
		<th>Rôle</th>
		<th><img src=\"../img/supprimer2.png\"  alt=\"Supprimer\" title=\"Supprimer\"/></th>
	</tr>
	</thead>
	<tbody>";
}
	
	$tabDroits = array("Contributeur","Adhérent","Modérateur","Animateur","Administrateur");
	while($row = $res->fetch())
	{
		echo  "<tr>
			<td>".$row['login']."</td>
			<td>".$row['nom']."</td>
			<td>".$row['prenom']."</td>
			<td><a href=\"../admin/user.php?u=".$row['idUtilisateur']."\">
					<img src=\"../img/loupe.png\"  alt=\"Voir utilisateur\" title=\"Voir cet utilisateur\"/>
				</a>
			</td>
			<td>";
			if($_SESSION["id"] != $row["idUtilisateur"] and $rang > $row['rang'])
			{
				echo"	<form method=\"POST\" action=\"#\" onChange=\"return promouvoir($(this));\" >
					<input type=\"hidden\" name=\"idUtilisateur\" id=\"idUtilisateur\" value=\"".$row['idUtilisateur']."\"/>
					<select name=\"rank\" id=\"rank\">";
					for($i = 0;$i < $rang; $i++)
						echo "<option value=\"".($i+1)."\" ".(($i+1) == $row["rang"] ? "selected=\"selected\"" : "").">".$tabDroits[$i]."</option>";
					echo "</select>
				</form>";
			}
			else
			{
				echo $tabDroits[($row["rang"]-1)];
			}		
				
			echo "</td>
			<td>";
				if($_SESSION["id"] != $row["idUtilisateur"] and $rang > $row['rang'])
				{
					echo "<form method=\"POST\" action=\"#\" onSubmit=\"return promouvoir($(this));\" >
							<input type=\"hidden\" name=\"idUtilisateur2\" id=\"idUtilisateur2\" value=\"".$row['idUtilisateur']."\"/>
							<input type=\"submit\" value=\"Supprimer\" />
						</form>";
				}
			echo "</td>
		</tr>";	
	}
echo "</tbody>
</table><br/><br/>";
	
$res = $f->query("SELECT * FROM cadavre
					WHERE isEnCours = '1'
					ORDER BY dateDebut DESC;");
if($res->numRows()==0)
{
	echo "<div class=\"center\">".BMInfo("Aucun jeu du cadavre exquis en cours",false)."</div><br/><br/>";
}
else
{
	echo  "<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Cadavre exquis en cours</caption>
	<thead>
	<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
		<th>Date de début</th>
		<th><img src=\"../img/go.png\"  alt=\"Aller à\" title=\"Rejoindre le jeu en cours\"/></th>
		<th><img src=\"../img/supprimer2.png\"  alt=\"Promouvoir\" title=\"Promouvoir\"/></th>
	</tr>
	</thead>
	<tbody>";
	
	while($row = $res->fetch())
	{
		echo  "<tr><td>".datetostring($row['dateDebut'],true)."</td>
		<td>";
		if($row["isEnCours"])
			echo "<a href='../pages/ce.php?i=".$row['idCadavre']."' class='button' title='Accéder au jeu'>Accéder au jeu</a></td>";
		?>
		<td><form method="POST" action="#" onSubmit="return promouvoir($(this));" ><input type="hidden" name="idCadavre" id="idCadavre" value="<?php echo $row['idCadavre']; ?>"/><input type="submit" value="Supprimer" /></form>
	
		<?php	
	}	
	echo "</tbody>
	</table><br/><br/>";
}	
$resT = $f->query("SELECT * FROM texte WHERE isValide=0 AND isBrouillon=0;");
if($resT->numRows() == 0)
{
	echo "<div class=\"center\">".BMInfo("Aucun texte en attente de validation",false)."</div><br/><br/>";	
}
else
{
	echo  "<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Liste des textes en attente de validation</caption>
	<thead>
	<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
		<th>Titre</th>
		<th>Utilisateur</th>
		<th>Modifier</th>
		<th><img src=\"../img/valider.png\"  alt=\"Valider\" title=\"Valider\"/>
		<th><img src=\"../img/supprimer2.png\"  alt=\"Promouvoir\" title=\"Promouvoir\"/></th>
	</tr>
	</thead>
	<tbody>";
	
	while($rowT = $resT->fetch())
	{
		$res = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur=".$rowT['idUtilisateur'].";");
		if($res->numRows() == 0){
			$nomU="";
		}	
		else
		{
			$row = $res->fetch();
			$nomU=$row['nom'];
		}	
		echo "<tr><td>".$rowT['titre']."</td><td>".$nomU."</td><td><a href='#modifier' title='Modifier' onClick='modifierTexte(".$rowT["idTexte"].")'><img src=\"../img/loupe.png\"  alt=\"Voir texte\" title=\"Voir ce texte\"/></a></td>";
		?>
		<td>
			<form method="POST" action="#" onSubmit="return promouvoir($(this));" >
				<input type="hidden" name="idTexte" id="idTexte" value="<?php echo $rowT['idTexte']; ?>"/>
				<input type="submit" value="Valider" />
			</form>
		</td>
		<td>
			<form method="POST" action="#" onSubmit="return promouvoir($(this));" >
				<input type="hidden" name="idTexte2" id="idTexte2" value="<?php echo $rowT['idTexte']; ?>"/>
				<input type="submit" value="Supprimer" />
			</form>
		</td>
		</tr>
		<?php
	}
	echo "</tbody>
	</table><br/><br/>";
}

$res = $f->query("SELECT * FROM retour WHERE isValide=0;");
if($res->numRows() == 0)
{
	echo "<div class=\"center\">".BMInfo("Aucun retour en attente de validation",false)."</div><br/><br/>";
}
else
{
	echo  "<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Liste des retours en attente de validation</caption>
	<thead>
		<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
			<th>Utilisateur</th>
			<th>Texte</th>
			<th>Voir commentaire</th>
			<th><img src=\"../img/valider.png\"  alt=\"Valider\" title=\"Valider\"/>
			<th><img src=\"../img/supprimer2.png\"  alt=\"Promouvoir\" title=\"Promouvoir\"/></th>
		</tr>
	</thead>
	<tbody>";
	
	while($row = $res->fetch())
	{
		$resU2 = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur=".$row['idUtilisateur'].";");
		$rowU2 = $resU2->fetch();
		$resA2 = $f->query("SELECT * FROM texte WHERE idTexte=".$row['idTexte'].";");
		$rowA2 = $resA2->fetch();
		
		echo "<tr><td>".$rowU2['nom']."</td><td>".$rowA2['titre']."</td><td><a href='#modifier' title='Modifier' onClick='modifierRetour(".$row["idRetour"].")'><img src=\"../img/loupe.png\"  alt=\"Voir retour\" title=\"Voir ce retour\"/></a></td>";
		?>
		<td><form method="POST" action="#" onSubmit="return promouvoir($(this));" ><input type="hidden" name="idRetour" id="idRetour" value="<?php echo $row['idRetour']; ?>"/><input type="submit" value="Valider" /></form></td>
		<td><form method="POST" action="#" onSubmit="return promouvoir($(this));" ><input type="hidden" name="idRetour2" id="idRetour2" value="<?php echo $row['idRetour']; ?>"/><input type="submit" value="Supprimer" /></form></td>
		<?php
		echo "</tr>";
	}	
	echo "</tbody>
	</table><br /><br />";

}
$res = $f->query("SELECT * FROM news;");
echo  "<div id=\"feedbackNews\"></div>
		<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Liste des news</caption>
		<thead>
		<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
			<th>
				<a href =\"#ajouter\" onClick=\"return ajouterNews();\"><img src=\"../img/ajouter.png\"  title =\"Ajouter une news\" /></a>
			</th>
			<th>Titre</th>
		</tr>
		</thead>
		<tbody>";
if($res->numRows() == 0)
{
	echo "<td colspan=\"2\">Aucune news pour le moment</td>";
}else
{
	while($row = $res->fetch())
	{		
		echo "<tr>
				<td>
					<a href =\"#modifier\" onClick=\"return modifierNews(".$row["idNews"].");\" ><img src=\"../img/modifier.png\" alt=\"Modifier\" title=\"Modifier cette news\"/></a>
					<a href =\"#supprimer\" onClick=\"return supprimerNews(".$row["idNews"].");\"><img src=\"../img/supprimer.png\" alt=\"Supprimer\" title=\"Supprimer cet atelier\"/></a></td>
				<td>".$row['titreN']."</td>
			</tr>";
	}
	
}	
	echo "</tbody>
	</table><br/><br />";
		
					

$res = $f->query("SELECT idTheme,intitule 
				FROM theme
				ORDER BY intitule;");
if($res->numRows() == 0)
{
	echo "<div class=\"center\">".BMInfo("Aucun Thème",false)."<br/><br/>
		<button id=\"newTheme\"><img src=\"../img/ajouter.png\" /> Nouveau thème</button>
		</div>
		<div id=\"feedbackTheme\"></div>";
}
else
{
	echo  "<div id=\"feedbackTheme\"></div>
	<table class=\"tab-tab\"><caption class=\"ui-corner-all ui-state-active\">Liste des thèmes</caption>
	<thead>
		<tr class=\"ui-tabs-nav ui-accordion ui-state-default tab-title\">
			<th>Nom</th>
			<th><img src=\"../img/modifier2.png\"  alt=\"Modifier\" title=\"Modifier\"/></th>
			<th><img src=\"../img/supprimer2.png\"  alt=\"Supprimer\" title=\"Supprimer\"/></th>
		</tr>
	</thead>
	<tbody>";

	while($row = $res->fetch())
	{
		echo "<tr>
			<td>".$row["intitule"]."</td>
			<td><a href='#modifier' class='button' title='Modifier' onClick='modifierTheme(".$row["idTheme"].")'>Modifier</a></td>";
		?>
		<td><form method="POST" action="#" onSubmit="return promouvoir($(this));" ><input type="hidden" name="idTheme2" id="idTheme2" value="<?php echo $row['idTheme']; ?>"/><input type="submit" value="Supprimer" /></form></td></tr>
		<?php
			
			
		
	}	
	echo "</tbody>
	</table><br /><br />";
}



$f->displayFooter();
?>