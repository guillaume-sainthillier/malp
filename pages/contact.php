<?php

require_once "../class/fen.class.php";

$f = new fen("Contact",0);

$f->displayHeader();

?>

<h1>Contact</h1>
<br /><br />
<p>
	tél : Aurélie au 06 81 51 31 06 <br/>
	<a href="mailto:lesmotsalapelle@gmail.com" title="Envoyer un mail à l'association">lesmotsalapelle@gmail.com</a><br/>
	Facebook : <a href="https://www.facebook.com/LesMotsALaPelle" title="Ouvre la page Facebook de l'association les mots à la pelle">Page Facebook</a>
</p>
          
          
<?php
	$f->displayFooter();
?>