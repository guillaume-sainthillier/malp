<?php
require_once __DIR__ ."/../class/res.class.php";
class DB
{
	private $connection; // Objet PDO

	public $lastErrno; // Dernier numéro d'erreur 
	public $lastError; // Dernier message d'erreur
	
	/**
		db : Initialise un objet traitant une requête SQL
	**/
	public function __construct()
	{
		require_once __DIR__ ."/../dyn/database.inc.php";

			$dns = 'mysql:host='.$infos["adresse"].';dbname='.$infos["base"];
			$utilisateur = $infos["login"];
			$motDePasse = $infos["password"];
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES UTF8");
			$this->connection = new PDO( $dns, $utilisateur, $motDePasse, $options );
	}
	
	/**
		change_base : Change de base de données
						- base : Nom de la base de données
	**/
	public function change_base($base)
	{
		$this->query("USE ".$base);
	}
	
	/**
		query : Effectue une requête SQL(MySQL)
				- sql : Requête SQL à traiter
	**/
	public function query($sql)
	{
		$res = $this->connection->query($sql);
		if(!$res)
		{
			$infos = $this->connection->errorInfo();
			$this->lastErrno = $infos[1];
			$this->lastError = $infos[2];
			return false;
		}
		
		return new res($res);
	}
	
	/**
		lastId : Retourne le dernier ID affecté
	**/
	public function lastId()
	{
		return $this->connection->lastInsertId();
	}
}

?>