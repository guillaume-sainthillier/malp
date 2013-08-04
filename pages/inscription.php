<?php

require_once "../class/fen.class.php";

$f = new fen("Inscription",0);

$f->addJS(array("init_inscription();"));
$f->displayHeader();

?>

<br />
<br />
<br />
<div class="center ui-corner-all ui-widget-content formInscription">
<span id="feedback"></span>
<form method="POST" action="#" onSubmit="return inscription($(this));" >
	<br />
	<label for="login" class="withimg"><span class="rouge">*</span> Nom de compte: </label> <input type="text" value="" name="login" id="login" class="withimg"/> 
	<img src="../img/aide.png" id="aidelogin" class="imgaide" alt="Image d'aide"/><br />
	<label for="mdp" class="withimg"><span class="rouge">*</span> Mot de passe: </label> <input type="password" value="" name="mdp" id="mdp" class="withimg"/>
	<img src="../img/aide.png" id="aidepwd" class="imgaide" alt="Image d'aide"/><br /><br />
	<label for="nom"><span class="rouge">*</span> Nom: </label> <input type="text" value="" name="nom" id="nom" /><br /><br />
	<label for="prenom"><span class="rouge">*</span> Prénom: </label> <input type="text" value="" name="prenom" id="prenom" /><br /><br />
	
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-corner-all ui-widget-content ui-state-active">Informations complémentaires</legend>
		<label for="tel">Téléphone: </label> <input type="text" value="" name="tel" id="tel" /><br />
		<label for="mail" class="withimg">Mail: </label> <input type="text" value="" name="mail" id="mail" class="withimg"/>
		<img src="../img/aide.png" id="aidemail" class="imgaide" alt="Image d'aide"/><br /><br />
		<label for="adresse">Adresse: </label> <input type="text" value="" name="adresse" id="adresse" /><br /><br />
		<label for="cp">Code Postal: </label> <input type="text" value="" name="cp" id="cp" /><br /><br />		
		<label for="ville">Ville: </label> <input type="text" value="" name="ville" id="ville" /><br /><br />	
	</fieldset>
	<br />
	<input type="submit" value="S'inscrire" /><br /><br />
	
</form>
</div>


<?php

$f->displayFooter();
?>