<?php


	define("REGEX_MAIL","/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/i");
	define("REGEX_USER","/^([\w\d_-]){3,18}$/i");
	define("REGEX_PWD","/^([\w\d_-]){6,18}$/i");
	define("REGEX_TEL","/^\+?([\d]){10,13}$/");
	
	
	/**
		BMOK : Retourne le code HTML d'un message de confirmation de bon fonctionnement
						- message	: Contenu du message
						- fullWidth	: Vrai par défaut, n'affiche pas le message d'erreur sur toute sa longueur si faux
	**/
	function BMOK($message, $fullWidth = true)
	{
		return "<div class=\"message ui-widget ui-corner-all ui-state-highlight ui-state-valid bordervert ".(!$fullWidth ? "displayib": "")."\" >
		<span class=\"ui-icon ui-icon-circle-check\"></span>
			<span class=\"text\">".$message."</span>
		</div>";
	}

	/**
		BMInfo : Retourne le code HTML d'un message d'information
						- message	: Contenu du message
						- fullWidth	: Vrai par défaut, n'affiche pas le message d'erreur sur toute sa longueur si faux
	**/
	function BMInfo($message, $fullWidth = true)
	{
		return "<div class=\"message ui-widget ui-corner-all ui-state-default ui-state-valid ".(!$fullWidth ? "displayib": "")."\" >
		<span class=\"ui-icon ui-icon-info\"></span>
			<span class=\"text\">".$message."</span>
		</div>";
	}
	
	/**
		BMErreur : Retourne le code HTML d'un message d'erreur
						- message : Contenu du message
						- fullWidth : Vrai par défaut, n'affiche pas le message d'erreur sur toute sa longueur si faux
	**/
	function BMErreur($message, $fullWidth = true)
	{
		return "<div class=\"message ui-widget ui-corner-all ui-state-error ".(!$fullWidth ? "displayib": "")."\" >
		<span class=\"ui-icon ui-icon-alert\" ></span>
			<span class=\"text\">".$message."</span>
		</div>";
	}
	
	/**
		e : Retourne un string sécurisé pour une requête en base de donnée
				- string : Variable à protéger (type entier, string)
	**/
	function e($string)
	{
		// On regarde si le type de string est un nombre entier (int) 
		// if(ctype_digit($string)) //Pose problème pour un numéro de tel qui commence par 0
		// {
			// $string = intval($string);
		// }
		// Pour tous les autres types
		// else
		{
			$string = trim($string);
			$string = mysql_real_escape_string($string);
		}		
		return $string;
	}
	
	/**
		s : Retourne un string formaté pour l'affichage HTML 
				- string : Variable à formater
	**/
	function s($string)
	{
		$string = htmlspecialchars_decode($string);
		return str_replace('\_','_',htmlspecialchars($string));

	}

	/**
		datetostring : Retourne une date au format anglais
							- texte : Date au format français (DD/MM/YYYY)
	**/
	function datetostring($texte, $withHeures = false, $withSecondes = false)
	{
		if($texte == "0000-00-00" or $texte == "0000-00-00 00:00:00") return "";
		$texte = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#isU','$3/$2/$1',$texte);
		if(!$withHeures)
			return substr($texte,0,10);
		if(!$withSecondes)
			$texte =  preg_replace("#(\d{1,2}):(\d{1,2}):(\d{1,2})$#i","à $1h$2",$texte);
		else
			$texte =  preg_replace("#(\d{1,2}):(\d{1,2}):(\d{1,2})$#i","à $1h$2:$3",$texte);
			
		return $texte;
	}
	
	/**
		stringtodate : Retourne une date au format français
							- texte : Date au format anglais (YYYY-MM-DD)
	**/
	function stringtodate($texte, $withHeures = false, $withSecondes = false)
	{
		if($texte == "") return "0000-00-00".($withSecondes ? "00:00": "00:00:00");
		$texte =  preg_replace('#([0-9]{2})/([0-9]{2})/([0-9]{4})#isU','$3-$2-$1',$texte);
		if(!$withHeures)
		{
			return substr($texte,0,10);
		}
		
		return $texte;
	}

?>