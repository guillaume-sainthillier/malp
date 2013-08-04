<?php

require_once "../class/fen.class.php";

$f = new fen("Gestion utilisateur",3);
$f->addJS(array("init_modif();"));
$f->displayHeader();

if(!isset($_GET["u"]))
	$f->_die("Mauvais emploi","La page que vous demandez a mal été appelée");
	
$res = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur='".e($_GET['u'])."' ;");
if(! $row = $res->fetch())
{
	$f->_die("Utilisateur introuvable","L'utilisateur demandé est introuvable");
}
else
{

?>
	<br />
	<br />
	<br />
	<div id="feedback"></div>
	<fieldset class="center ui-widget ui-widget-content ui-corner-all formInscription">
	<legend class="ui-corner-all ui-widget-content ui-state-active">Compte</legend>
	<form method="POST" action="#" onSubmit="return modification($(this));" >
		<br />
		<label for="login" >Nom de compte: </label> <input type="text" value="<?php echo $row['login'];?>" name="login" id="login" disabled="disabled" /> <br /><br />
		<label for="mdp" ><span class="rouge">*</span> Mot de passe: </label> <input type="password" value="<?php echo $row['mdp'];?>" name="mdp" id="mdp" /> <br /><br />
		<label for="nom"><span class="rouge">*</span> Nom: </label> <input type="text" value="<?php echo $row['nom'];?>" name="nom" id="nom"  /><br /><br />
		<label for="prenom"><span class="rouge">*</span> Prénom: </label> <input type="text" value="<?php echo $row['prenom'];?>" name="prenom" id="prenom"  /><br /><br />
		
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
			<legend class="ui-corner-all ui-widget-content ui-state-active">Informations complémentaires</legend>
			<label for="tel">Téléphone: </label> <input type="text" value="<?php echo $row['tel'];?>" name="tel" id="tel"  /><br />
			<label for="mail" class="withimg">Mail: </label> <input type="text" value="<?php echo $row['mail'];?>" name="mail" id="mail" class="withimg"  />
			<img src="../img/aide.png" id="aidemail" class="imgaide" alt="Image d'aide"/><br /><br />
			<label for="adresse">Adresse: </label> <input type="text" value="<?php echo $row['adresse'];?>" name="adresse" id="adresse"  /><br /><br />
			<label for="cp">Code Postal: </label> <input type="text" value="<?php echo $row['codeP'];?>" name="cp" id="cp"  /><br /><br />		
			<label for="ville">Ville: </label> <input type="text" value="<?php echo $row['ville'];?>" name="ville" id="ville"  /><br /><br />	
		</fieldset>
		<br />
		<?php
			echo "<input type=\"hidden\" name=\"idUser\" value=\"".$_GET["u"]."\" />";
		?>
		<input type="submit" value="Modifier" /><br /><br />
	</form>
	<a href="../admin/admin.php" class="button" title="Accéder à l'administration">Retour à l'administration</a><br /><br />
	</fieldset>

	
<?php

}
$f->displayFooter();

?>