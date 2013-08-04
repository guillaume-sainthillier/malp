<?php

require_once "../class/fen.class.php";

$f = new fen("Mon Compte",1);
$f->addJS(array("init_modif();"));
$f->displayHeader();

$res = $f->query("SELECT * FROM utilisateur WHERE idUtilisateur ='".$_SESSION['id']."' ;");
if(! $row = $res->fetch())
	$f->_die("Utilisateur introuvable","Vous n'êtes pas répertorié dans notre base de données");

?>
	<br />
	<br />
	<br />
	<fieldset class="center ui-widget ui-widget-content ui-corner-all formInscription">
		<legend class="ui-corner-all ui-widget-content ui-state-active">Mon compte</legend>
	<span id="feedback"></span>
	<form method="POST" action="#" onSubmit="return modification($(this));" >
		<br />	
		<br />
		<label for="login" ><span class="rouge">*</span> Nom de compte: </label> <input type="text" value="<?php echo $row['login'];?>" name="login" id="login" disabled="disabled" /><br /> 
		<label for="mdp" class="withimg"><span class="rouge">*</span> Mot de passe: </label> <input type="password" value="<?php echo $row['mdp'];?>" name="mdp" id="mdp" class="withimg"/>
		<img src="../img/aide.png" id="aidepwd" class="imgaide" alt="Image d'aide"/><br /><br />
		<label for="nom"><span class="rouge">*</span> Nom: </label> <input type="text" value="<?php echo $row['nom'];?>" name="nom" id="nom" /><br /><br />
		<label for="prenom"><span class="rouge">*</span> Prénom: </label> <input type="text" value="<?php echo $row['prenom'];?>" name="prenom" id="prenom" /><br /><br />
		
		<fieldset class="ui-widget ui-widget-content ui-corner-all">
			<legend class="ui-corner-all ui-widget-content ui-state-active">Informations complémentaires</legend>
			<label for="tel">Téléphone: </label> <input type="text" value="<?php echo $row['tel'];?>" name="tel" id="tel" /><br />
			<label for="mail" class="withimg">Mail: </label> <input type="text" value="<?php echo $row['mail'];?>" name="mail" id="mail" class="withimg"/>
			<img src="../img/aide.png" id="aidemail" class="imgaide" alt="Image d'aide"/><br /><br />
			<label for="adresse">Adresse: </label> <input type="text" value="<?php echo $row['adresse'];?>" name="adresse" id="adresse" /><br /><br />
			<label for="cp">Code Postal: </label> <input type="text" value="<?php echo $row['codeP'];?>" name="cp" id="cp" /><br /><br />		
			<label for="ville">Ville: </label> <input type="text" value="<?php echo $row['ville'];?>" name="ville" id="ville" /><br /><br />	
		</fieldset>
		<br />
		<input type="submit" value="Modifier mon compte" /><br /><br />
	</fieldset>
	<br/>
		
	<fieldset class="center ui-widget ui-widget-content ui-corner-all formInscription">
		<legend class="ui-corner-all ui-widget-content ui-state-active">Mes ateliers</legend>
		<br/><br/>
		<?php
			$res = $f->query("SELECT * FROM atelier 
							WHERE idAtelier IN (
								SELECT idAtelier 
								FROM participantsatelier 
								WHERE idUtilisateur ='".$_SESSION['id']."'
							);");
			while($row = $res->fetch())
			{
				echo " <a href='' class='button' title='Voir atelier'>".$row['nom']."</a>";
			}	
				
		?>
	</fieldset>
		<br/>
	<fieldset class="center ui-widget ui-widget-content ui-corner-all formInscription">
		<legend class="ui-corner-all ui-widget-content ui-state-active">Mes jeux</legend>
		<br/><br/>

	</fieldset>

<?php

$f->displayFooter();
?>