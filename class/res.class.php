<?php

class res {

	private $res;// Objet PDOStatement
	
	/**
		res : Initialise un objet résultant d'une requête SQL
	**/
	function __construct($resultat)
	{
		$this->res = $resultat;
	}
	
	/**
		fetch: Retourne une ligne d'enregistrement (tab;eau associatif par défaut)
				- fetchAssoc : Vrai par défaut, retourne un tableau indicé si faux
	**/
	function fetch($fetchAssoc = true)
	{
		if($fetchAssoc)
			$fetch = PDO::FETCH_ASSOC;
		else
			$fetch = PDO::FETCH_NUM;
		return $this->res->fetch($fetch);
	}
	
	/**
		numRows : Retourn le nombre de lignes affectées par la requête
	**/
	function numRows()
	{
		return $this->res->rowCount();
	}
}
?>