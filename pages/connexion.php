<?php

require_once "../class/fen.class.php";

$f = new fen("Connexion",0);

$f->displayHeader();

?>

<br />
<br />
<br />
<div class="center ui-corner-all ui-widget-content formConnexion">
<span id="feedback"></span>
<form method="POST" action="#" onSubmit="return connection();" >
	<br /><label for="login">Login: </label> <input type="text" value="" name="login" id="login" /><br /><br />
	<label for="mdp">Mot de passe: </label> <input type="password" value="" name="mdp" id="mdp" /><br /><br />
	<input type="submit" value="Connexion" /><br /><br />
</form>
	<br />
	<a href="../pages/inscription.php" title="Aller sur la page d'inscription" class="button">Pas encore inscrit ?</a>
	<a href="#" title="Mot de passe perdu ?" class="button">Mot de passe perdu ?</a><br /><br />
</div>


<?php

$f->displayFooter();
?>