<?php

require_once "../class/fen.class.php";

$f = new fen("Gestion textes publiés",3);
$f->displayHeader();

$res = $f->query("SELECT * FROM Texte WHERE idTexte='".$_GET['idTexte']."' ;");
if(! $row = $res->fetch())
{
	echo "Erreur lors du chargement de la page<br /><br />";
}
else
{
	$resU = $f->query("SELECT * FROM Utilisateur WHERE idUtilisateur='".$row['idUtilisateur']."' ;");
	$rowU=$resU->fetch();
	$resA=$f->query("SELECT * FROM Atelier WHERE idAtelier='".$row['idAtelier']."' ;");
	$rowA=$resA->fetch();
	$resT=$f->query("SELECT * FROM Theme WHERE idTheme='".$row['idTheme']."' ;");
	$rowT=$resT->fetch();
?>
	<br />
	<br />
	<br />
	<div class="center ui-corner-all ui-widget-content formInscription">
	<p class="ui-corner-all ui-widget-content ui-state-active"><font size="4">Texte à valider</font></p>
	<span id="feedback"></span>
	<br/><br/>De :  <?php echo "<a href =\"../admin/user.php?u=".$rowU['idUtilisateur']."\">".$rowU['nom']."</a><br/><br/>";?>
	Thème : <?php echo $rowT['intitule'];?><br/><br/>
	Pour l'atelier : <?php echo $rowA['nom'];?><br/><br/>
	<form method="POST" action="#" onSubmit="return promouvoir($(this));" >
		<label for="titreT">Titre : </label> <input type="text" value="<?php echo $row['titre'];?>" name="titreT" id="titreT" /> <br/><br/>
		<label for="contenuT"> Contenu : </label> <textarea name="contenuT" id="contenuT" rows="10" cols="40" /><?php echo $row['contenu'];?></textarea> <br/><br/>
		<input type="hidden" name="idTexteM" id="idTexteM" value="<?php echo $row['idTexte']; ?>"/>
		<input type="submit" value="Modifier le texte"/>
	</form>
	</div>
<?php
	echo '<br/><center><a href="../admin/admin.php" class="button" title="Accéder à l\'administration">Retour à l\'administration</a></center><br />';

}
$f->displayFooter();
?>
