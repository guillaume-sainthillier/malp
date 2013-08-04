<?php

require_once "../class/fen.class.php";


$f = new fen("Jeu du Poème Dada",1);


$f->addLibJs(array("ckeditor/ckeditor.js","ckeditor/adapters/jquery.js"));
$f->addJs("init_publication();");
$f->displayHeader();

?>

<h1>Poème Dada</h1>
<br />
<br />
<br />

<?php
	
echo "<form method=\"POST\" action=\"".$_SERVER["PHP_SELF"]."\" onSubmit=\"return send_melanger()\">

<span id=\"feedback\"></span>
	<br /><br />
	<textarea id=\"texte\" name=\"texte\"></textarea>
	<br />

	<div class=\"center\">
		<input type=\"submit\" value=\"Publier\" /> 
		<input type=\"button\" onClick=\"melangerTexte();\" value=\"Mélanger !\" />
	</div>
	<br /><br />
	<blockquote id=\"texteMelange\" name=\"texteMelange\"></blockquote>
	
</form>";

	$f->displayFooter();
?>