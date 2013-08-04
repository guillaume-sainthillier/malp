<?php
// prevent the server from timing out
set_time_limit(0);


require_once __DIR__ ."/../class/db.class.php";
require_once __DIR__ ."/../class/CadavreWebSocket.class.php";


function onMessage($server,$clientID, $message, $messageLength, $binary)
{
	if ($messageLength == 0) 
	{
		$server->close($clientID);
		return;
	}

	$retour = json_decode($message);
	
	if(!isset($retour->data) or !isset($retour->action))
	{
		$server->send($clientID, "error","Mauvaise requête");
		return;
	}
	
	$data = $retour->data;
	$action = strtolower($retour->action);
	
	
	$fonctions_allowed = array("close_game","phrase","close_chat","load_chat",
								"chat","join","passer","move_to_spec",
								"move_to_player","deconnecter","close_game");
	if(!in_array($action,$fonctions_allowed) or !method_exists($server,$action))	
		$server->send($clientID, "error","Mauvaise action: ".$action);
	else
		$server->$action($clientID, $data);
}

// when a client connects
function onOpen($server,$clientID)
{
	$ip = long2ip( $server->clients[$clientID][6] );
	$server->log( "$ip s'est connecte" );
}

// when a client closes or lost connection
function onClose($server,$clientID, $status) 
{
	$ip = long2ip( $server->clients[$clientID][6] );
	$server->log( "$ip s'est deconnecte" );
	$server->deconnecter($clientID);
}


// start the server

$db = new DB();
$server = new CadavreWebSocket($db);
$server->bind('message', 'onMessage');
$server->bind('open', 'onOpen');
$server->bind('close', 'onClose');

// $ip = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "192.168.1.74";
$ip = isset($_SERVER["SERVER_ADDR"]) ? "localhost" : "192.168.1.74";
$server->log("En attente de connexion...".$ip);
$server->startserver($ip, 9300);

?>