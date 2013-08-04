<?php
header("HTTP/1.0 404 Not Found");
require_once "../class/fen.class.php";

$f = new fen("Page non trouvée",0);
$f->displayHeader();

echo "
<br />
<br />
<br />
<br />
<br />
<div class=\"center\">";
	if(isset($_GET["page"]))
	
		echo BMErreur("La page <b>http://".$_SERVER["HTTP_HOST"].$_GET["page"]."</b> est introuvable",false);
	else
		echo BMErreur("La page que vous demandez est introuvable",false);
	
	
echo "</div>";
$f->displayFooter();
?>