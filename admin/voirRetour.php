<?php

require_once "../class/fen.class.php";

$f = new fen("Gestion commentaires",3);
$f->displayHeader();

$res = $f->query("SELECT * FROM Retour WHERE idRetour='".$_GET['idRetour']."' ;");

if(! $row = $res->fetch())
{
	echo "Erreur lors du chargement de la page<br /><br />";
}
else
{
		$resU2 = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur=".$row['idUtilisateur'].";");
		$rowU2 = $resU2->fetch();
		$resA2 = $f->query("SELECT * FROM texte WHERE idTexte=".$row['idTexte'].";");
		$rowA2 = $resA2->fetch();
		?>
		<br />
	<br />
	<br />
	<div class="center ui-corner-all ui-widget-content formInscription">
	<p class="ui-corner-all ui-widget-content ui-state-active"><font size="4">Commentaire à valider</font></p>
	<span id="feedback"></span>
	<br/><br/>De :  <?php echo "<a href =\"../admin/user.php?u=".$rowU2['idUtilisateur']."\">".$rowU2['nom']."</a><br/><br/>";?>
	Sur le texte : <?php echo $rowA2['titre'];?><br/><br/>
	<form method="POST" action="#" onSubmit="return promouvoir($(this));" >
		<label for="retour"> Commentaire : </label> <textarea name="retour" id="retour" rows="10" cols="40" /><?php echo $row['commentaire'];?></textarea> <br/><br/>
		<input type="hidden" name="idRetourM" id="idRetourM" value="<?php echo $row['idRetour']; ?>"/>
		<input type="submit" value="Modifier le commentaire"/>
	</form>
	</div>

<?php
echo '<br/><center><a href="../admin/admin.php" class="button" title="Accéder à l\'administration">Retour à l\'administration</a></center><br />';
}
$f->displayFooter();
?>

