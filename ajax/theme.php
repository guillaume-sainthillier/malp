<?php
require_once "../class/fenajax.class.php";

	$f = new fenAjax("Connexion",3);
	
	if(isset($_POST["idTheme"]) && isset($_POST['intitule']))
	{
		$res = $f->query("UPDATE theme SET intitule='".$_POST['intitule']."' WHERE idTheme= '".e($_POST["idTheme"])."';");
		$f->ajaxOK("","Theme modifié",false);
	}
	$f->endAjax();
?>