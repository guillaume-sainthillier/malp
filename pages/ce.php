<?php


require_once "../class/fen.class.php";

$f = new fen("Cadavre exquis",2);


if(!isset($_GET["i"]))
	$f->_die("Mauvais emploi de la page","Mauvais emploi de la page");

$id = $_GET["i"];

$res = $f->query("SELECT isEnCours FROM cadavre WHERE idCadavre = '".e($id)."' ;");
if(! $row = $res->fetch())
	$f->_die("Jeu introuvable","Le jeu demandé est introuvable");
if(! $row["isEnCours"])
	$f->_die("Jeu terminé","La partie en cours est terminée<br /><a class=\"button\" href=\"../pages/cadavre.php\">Revenir au jeu</a>");


$f->addLibJs(array("fancywebsocket.js","socket_cadavre.js")); //'ws://127.0.0.1:9300'
$f->addJs("init_jeu_cadavre('".base64_encode($_SESSION["id"])."','".base64_encode($id)."');");
$f->displayHeader();
	
echo "<div id=\"jeuCadavre\" class=\"frameCadavre ui-widget ui-widget-content ui-corner-all\">
	<div id=\"logs\" class=\"ui-widget ui-widget-content ui-corner-all informations\">
		Veuillez patienter...<br />
	</div>
	<div id=\"actions\" class=\"actions\">
		<button class=\"\" id=\"closeGame\" ><img src=\"../img/stop.png\" /> Terminer le jeu</button>
		<button class=\"\" id=\"loadChat\" ><img src=\"../img/chat.png\" /> Démarrer le chat</button>
		<button class=\"\" id=\"closeChat\" ><img src=\"../img/chat.png\" /> Arrêter le chat</button>
		<button class=\"\" id=\"movetoplayer\" ><img src=\"../img/play.png\" /> Reprendre</button>
		<button class=\"\" id=\"movetospec\" ><img src=\"../img/yeux.png\" /> Observer</button>
		<button class=\"\" id=\"passerTour\" ><img src=\"../img/skip.png\" /> Passer mon tour</button>
	</div>
	<div class=\"fright membres \">
		<div class=\"ui-widget ui-widget-content ui-corner-all\">
			<span id=\"nbMembres\">0</span><span> Joueur</span>
			<ul>
			</ul>
		</div>
		<div class=\"ui-widget ui-widget-content ui-corner-all\">
			<span id=\"nbSpec\">0</span><span> Spectateur</span>
			<ul>
			</ul>
		</div>
	</div>
	<div class=\"jeu\">
		<div class=\"phrase\"></div>
	</div>
	<div class=\"texteSaisi\">
		<form method=\"POST\" action=\"#\" onSubmit=\"envoyer_msg(); return false;\">
			<input type=\"text\" id=\"texte\" size=\"80\" /> 
			<input type=\"submit\" value=\"Envoyer\" id=\"sendTexte\" class=\"\" />
		</form>
	</div>
	<div class=\"chat\">
		<fieldset class=\"ui-widget ui-widget-content ui-corner-all\">
			<legend class=\"ui-corner-all ui-widget-content ui-state-active\">Salon de chat</legend><br />
			<form method=\"POST\" action=\"#\" onSubmit=\"send_chat(); return false;\">
				<input type=\"text\" size=\"80\" class=\"sendChat\" /> 
				<input type=\"submit\" value=\"Envoyer\" />
			</form>
			<br />
			<div class=\"chatTexte\"></div>
		</fieldset>
	</div>
	<div class=\"resultat\">
		<div class=\"center\">
			<a href=\"../pages/cadavre.php\" title=\"Accéder aux détails du jeu\" class=\"button\" >Revenir au jeu du cadavre exquis</a>
		</div>
	</div>
</div>";

$f->displayFooter();
?>