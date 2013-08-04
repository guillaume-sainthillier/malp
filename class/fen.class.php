<?php

require_once "../class/db.class.php";
require_once "../dyn/fonctions.inc.php";

class fen
{
	protected $charset; //Format d'encodage de la page
	protected $mode; // Mode de développement (PRODUCTION, VERBOSE, DEBUG)
	protected $rangMinAdmin;// Rang minimum d'administration pour consulter la page
	protected $theme; // Theme CSS jQuery UI
	protected $js; //Array contenant du javascript
	protected $libJs; //Array contenant du javascript
	
	public $titre;// Titre de la page
	public $db; //Objet base de données
	public $nomSite; // Nom du site
	public $filAriane; //Fil d'arianerealpath(__dir__ ."/../")
	
	/**
		fen : Initialise un objet fenêtre 
			- titre: Titre de la page
			- rangAdmin: rang d'administration minimum pour accéder à cette page
			- filAriane : HTML du fil d'ariane
	**/
	public function __construct($titre, $rangAdmin = 0, $filAriane = false)
	{
		ob_start();
		setlocale(LC_TIME, 'fr_FR','fra');
		$this->titre = $titre;
		$this->charset = "utf-8";
		$this->mode = "DEBUG";
		$this->nomSite = "Les mots à la pelle";
		$this->theme = "redmond";
		$this->rangMinAdmin = $rangAdmin;
		require_once "../dyn/session.inc.php";

		$this->_initFlux();
		$this->_gestionBD();
		
		if(!$filAriane)
			$this->filAriane = "<a href=\"../pages/index.php\" title=\"Revenir à la page d'accueil\">Accueil</a>".(strtolower($titre) != "accueil" ? " > <i>".$titre."</i>" : "");
		else
			$this->filAriane = $filAriane;
			
		$this->js = array();
		$this->libJs = array();
		$this->libCss = array();		
		
		if(isset($_GET["params"]))
		{
			$params = preg_split("#/#",$_GET["params"]);
			for($i = 0;$i < count($params); $i +=2)
			{
				if(isset($params[$i+1]))
					$_GET[$params[$i]] = $params[$i+1];
				else
					$_GET[$params[$i]] = "";
			}			
			unset($_GET["params"]);
		}
		
		
		$root = "/".preg_replace("#^".$_SERVER['DOCUMENT_ROOT']."#i","",$_SERVER['SCRIPT_FILENAME']);
		$root = preg_replace("#(.*)/([^/]+)/([^/]+)$#","$1/",$root);
		
		$this->root = $root;

		if(! $this->verifDroits() )
			$this->_die("Droits insuffisants","Vous ne possédez pas les droits nécéssaires pour effectuer cette action");
			
		if($rangAdmin >= 3)
			$this->libJs[] = "admin.js";
	}


/**


	Fonctions d'affichage


**/


	/**
		displayHeader() : Vérifie les droits d'authentification et affiche le contenu HTML de l'entête si flag à html
	**/
	public function displayHeader()
	{
		$html = ob_get_contents();
		if($html != "")
		{
			ob_end_clean();
			ob_start();
			$this->_die("Une erreur est survenue",$html);
		}

		
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\">
			<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html;charset=".$this->charset."\" />
				<meta name=\"description\" content=\"".$this->nomSite."\" />
				<meta name=\"keywords\" content=\"".$this->nomSite.", jeu, expression, mots, lalalal\" />
				<meta http-equiv=\"Content-Language\" content=\"fr\" />
				<title>".($this->nomSite)." - ".($this->titre)."</title>
				<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"../img/favicon.ico\" />
				<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../css/".$this->theme."/jQuery-ui-1.10.0.min.css\" />
				<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../css/style.css\" />";
				foreach($this->libCss as $css)
					echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"../css/".$css."\" />";
				
				
				echo "<script type=\"text/javascript\" src=\"../js/jQuery-1.8.3.js\" ></script>
				<script type=\"text/javascript\" src=\"../js/jQuery-ui-1.10.0.min.js\" ></script>
				<script type=\"text/javascript\" src=\"../js/jQuery.qtip-1.0.0-rc3.min.js\" ></script>
				<script type=\"text/javascript\" src=\"../js/jquery.ui.datepicker-fr.js\" ></script>
				<script type=\"text/javascript\" >site_root = \"".substr($this->root,0,-1)."\";</script>
				<script type=\"text/javascript\" src=\"../js/scripts.js\" ></script>";
				foreach($this->libJs as $js)
					echo "<script type=\"text/javascript\" src=\"../js/".$js."\"></script>";				
				
				if(count($this->js) > 0)
				{
					echo "<script type=\"text/javascript\">";
					echo "$(document).ready(function()
					{
						".implode("\n",$this->js)."
					});
					</script>
					";
				}
			echo "</head>
			<body>
			\t";
		
		$this->displayTopMenu();
		
		echo "<div id=\"contenu\">
				<a name=\"filAri\"></a>
				<div id=\"filAriane\">
					Vous êtes ici: ".$this->filAriane."<br /><br />
				</div>
				";
		$this->displayMenuDroite();	
	}
	
	/**
		displayTopMenu: Affiche le tableau de bord nord
	**/
	public function displayTopMenu()
	{
		echo "<div id=\"header\">
				<ul id=\"liensHaut\">
					<li><a href=\"#cont\">aller au contenu</a> | </li>
					<li><a href=\"#filAri\">aller au fil d'ariane</a> | </li>
					<li><a href=\"http://www.accessiweb.org\" title=\"Accéder au site de l'accessiweb\">politique d'accessibilité</a></li>
				</ul>
				<img src=\"../img/logo-LMLP.png\" alt=\"Les mots à la pelle.\"/>
				<img src=\"../img/laboratoire-enthousiaste-de.png\" alt=\"Laboratoire enthousiaste d'écritures.\" id=\"blason\"/>
			</div>
			<p><a name=\"ancreMenu\"></a></p>
			<ul id=\"menuHaut\" class=\"titre\">
				<li><a href=\"../pages/atelierstt.php\" title=\"Accéder aux ateliers tout publics\">Ateliers<br /> tous publics</a></li>
				<li><a href=\"../pages/ateliersenfantsado.php\" title=\"Accéder aux ateliers enfants-ados\">Ateliers<br /> enfants-ados</a></li>
				<li><a href=\"../pages/ateliersspe.php\" title=\"Accéder aux ateliers spécifiques\">Ateliers<br /> spécifiques</a></li>
				<li><a href=\"../pages/formation.php\" title=\"Accéder aux formations\">Formations</a></li>
				<li><a href=\"../pages/travauxredactionnels.php\" title=\"Accéder aux travaux rédactionnels\">Travaux<br /> rédactionnels</a></li>
				<li><a href=\"../pages/publiez.php\" title=\"Accéder à l'outil de publication\">Publiez<br /> vos textes</a></li>
			</ul>";
	}
	
	/**
		displayMenuDroite: Affiche le menu droite
	**/
	public function displayMenuDroite()
	{
		$res=$this->query("SELECT * FROM news;");
		
		echo "<div id=\"menuDroite\">
			<fieldset class=\"ui-corner-all\">
				<legend class=\"ui-corner-all ui-state-active\">News</legend>
				<ul>";
			while($row = $res->fetch())
			{
				echo "<li><a class=\"bgvert blanc\" href='#voirNews' onClick='voirNews(".$row["idNews"].")'>".$row['titreN']."</a></li>";
			}	

		echo	"</ul></fieldset>";
		echo "<fieldset class=\"ui-corner-all\">
				<legend class=\"ui-corner-all ui-state-active\">Intéractif</legend>
				<ul>";
			if($_SESSION["id"] > 0)
			{
				echo "<li><span class=\"vertli\" >Connecté sous ".$_SESSION["login"]."</span></li>";
				
				// rang d'admin : 1.Utilisateur < 2.Contributeur < 3.Modérateur < 4.Animateur < 5.Administrateur
				if($_SESSION["admin"] >= 3) //Modo
				{
					echo "<li><a class=\"bgrouge blanc\" href=\"../admin/atelier.php\">Gestion ateliers</a></li>
						<li><a class=\"bgrouge blanc\" href=\"../admin/admin.php\">Administration</a></li>\n";
	
				}
				echo "<li><a class=\"bgvert blanc\" href=\"../pages/jeux.php\">Jeux d'écriture en ligne</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/inscriptionAtelier.php\">S'inscrire à un atelier</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/listesAtelier.php\">Voir les ateliers</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/publication.php\">Publiez un texte</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/textes.php\">Textes publiés</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/compte.php\">Mon compte</a></li>
				<li><a class=\"bgvert blanc\" href=\"../pages/deconnexion.php\">Déconnexion</a></li>";
			}else
			{
				echo "<li><a class=\"bgvert blanc\" href=\"../pages/inscription.php\"	title=\"Accéder à l'espace inscription\">Inscription</a></li>
					<li><a class=\"bgvert blanc\" href=\"../pages/connexion.php\"		title=\"Accéder à l'espace adhérents\">Connexion</a></li>";
			}
			echo "</ul>
			</fieldset>
			<ul class=\"static\">
				<li><a class=\"bgrouge blanc\" href=\"../pages/espacelec.php\" 		title=\"Accéder à l'espace lecture\">espace lecture</a></li>
				<li><a  class=\"bgvert blanc\" href=\"../pages/association.php\" 	title=\"Connaître l'association\">l'association</a></li>
				<li><a  class=\"bgvert blanc\" href=\"../pages/adherents.php\" 		title=\"S'enregistrer en tant qu'adhérent\">devenez adhérent</a></li>
				<li><a  class=\"bgvert blanc\" href=\"../pages/contact.php\" 		title=\"Nous contacter\">contacts</a></li>
				<li><a class=\"vertli\" href=\"../pages/index.php\" 				title=\"Revenir à la page d'accueil\">accueil</a></li>
				<li><a class=\"vertli\" href=\"../pages/plan.php\" 					title=\"Consulter le plan du site\">plan du site</a></li>
				<li><a class=\"vertli\" href=\"../pages/aide.php\" 					title=\"Obtenir de l'aide\">aide</a></li>
			</ul>
		</div>
		<a name=\"cont\"></a>
		<div id=\"corps\">";
	}
	
	/**
		displayFooter : Affiche le pied de page
	**/
	public function displayFooter()
	{
		echo"
					</div>
				</div>
				<div id=\"footer\">
					<br />
					<ul>
						<li><a href=\"#ancreMenu\">Revenir au menu</a> | </li> 
							<li><a href=\"#filAri\">Revenir au fil d'ariane</a> | </li>
							<li><a href=\"../pages/index.php\" title=\"Revenir à l'accueil\">Accueil</a> | </li> 
							<li><a href=\"../pages/atelierstt.php\" title=\"Consulter les ateliers tout publics\">Ateliers tous publics</a> | </li>
							<li><a href=\"../pages/ateliersenfantsado.php\" title=\"Consulter les ateliers enfants ados\">Ateliers enfants-ados </a> | </li> 
							<li><a href=\"../pages/ateliersspe.php\" title=\"Consulter les ateliers spécifiques\">Ateliers spécifiques</a> | </li>
							<li><a href=\"../pages/formation.php\" title=\"Consulter les formations\">Formations</a> | </li> 
							<li><a href=\"../pages/travauxredactionnels.php\" title=\"Consulter les travaux rédactionnels\">Travaux rédactionnels</a> | </li> 
							<li><a href=\"../pages/publiez.php\" title=\"Accéder à la page de publication de vos textes\">Publiez vos textes</a> | </li> 
							<li><a href=\"../pages/espacelec.php\" title=\"Accéder à l'espace lecture\">Espace Lecture</a> | </li> 
							<li><a href=\"../pages/association.php\" title=\"Voir plus d'informations sur l'association\">L'association </a> | </li> 
							<li><a href=\"../pages/contact.php\" title=\"Accéder à la page de contacts\">Contacts</a> | </li> 
							<li><a href=\"../pages/adherents.php\" title=\"Accéder à l'espace adhérents\">Espace adhérents</a> | </li>
							<li><a href=\"../pages/plan.php\" title=\"Accéder au plan du site\">Plan du site</a> | </li> 
							<li><a href=\"../pages/aide.php\" title=\"Obtenir de l'aide\">Aide</a> | </li> 
							<li><a href=\"http://www.accessiweb.org\" title=\"Voir les mentions légales\">Mentions légales</a></li>
					</ul>  
				</div>
			</body>
		</html>";
		
		echo $this->_rewriteURL();
	}


/**


	Fonctions générales


**/


	/**
		addLibJs : Ajoute des scripts js dans le head
	**/
	public function addLibJs($libs)
	{
		if(is_array($libs))		
			$this->libJs = array_merge($this->libJs, $libs);
		else
			$this->libJs[] = $libs;
	}


	/**
		addJs : Ajoute du css dans le head
	**/
	public function addLibCss($libs)
	{
		if(is_array($libs))		
			$this->libCss = array_merge($this->libCss, $libs);
		else
			$this->libCss[] = $libs;
	}
	
	
	/**
		addJs : Ajoute du javascript dans le head
	**/
	public function addJs($fonctions)
	{
		if(is_array($fonctions))		
			$this->js = array_merge($this->js, $fonctions);
		else
			$this->js[] = $fonctions;
	}
	
	/**
		verifDroits : Retourne vrai si l'utilisateur peut consulter la page, faux sinon
	**/
	public function verifDroits()
	{
		return $this->rangMinAdmin <= $_SESSION['admin'];
	}

	/**
		query : Effectue une requête SQL(MySQL) et fait la gestion des erreurs
				- sql : Requête SQL à traiter
				- afficherRequete : Faux par défaut, affiche la requête passée en paramètre si vrai
	**/
	public function query($sql, $afficherRequete = false)
	{
		if($afficherRequete or $this->mode == "VERBOSE")
			echo BMInfo("La requête <b>".$sql."</b> est exécutée",false)."<br />";
		$res = $this->db->query($sql);
		if(!$res)
		{
			$this->_die("Erreur SQL",($this->mode != "PROD" ? BMInfo("Requête : $sql",false)." <br />":"" ).$this->db->lastError);
		}
		
		return $res;
	}

/**


	Fonctions internes


**/


	/**
		initFlux : Initialise la gestion des erreurs
	**/
	private function _initFlux()
	{
		if($this->mode == "PROD")
			error_reporting(0);
		else
			error_reporting(E_ALL);
	}
	
	/**
		gestionBD : Initialise la connection à la base de données
	**/
	private function _gestionBD()
	{
		try
		{
			$this->db = new DB();
		}catch ( Exception $e ) 
		{
			$this->_die("Erreur de connexion à la base de données",$e->getMessage());
		}
	}
	
	
	/**
		_die : Arrête tout traitement et affiche la page avec un message d'erreur et une entête
				- entente : Titre de l'erreur
				- msg : Détail de l'erreur
	**/
	public function _die($entete, $msg)
	{
		ob_end_clean();
		$this->titre = $entete;
		$this->displayHeader();
		echo $msg;
		$this->displayFooter();
		die();
	}
	
	/**
		_rewriteURL : Remplace tous les liens internes de la page en accord avec le .htaccess
	**/
	protected function _rewriteURL($html = false) // TODO 
	{
		if($html === false)
		{
			$html = ob_get_contents();
			ob_end_clean();
		}
		
		//Liens relatifs -> absolus
		$html = preg_replace("#(src|href)=(\"|')\.\./([^\"']+)(\"|')#i","$1=\"".$this->root."$3\"",$html);
		
		//Liens absolus internes -> URL selon le htaccess
		$html = preg_replace_callback("#(src|href)\s*=\s*(\"|')(".($this->root).")([^\"']+)/([^\"']+)\.php([^\"']*)\s*(\"|')#i",function($valeurs)
		{
			$fic = $valeurs[5];
			if($valeurs[6] != "")
			{
				if(preg_match("/^#(.+)$/",$valeurs[6])) //#fil
					$fic .= $valeurs[6];
				else //?get1=val1&get2=val2
					$fic .= preg_replace("/[?=&]/","/",$valeurs[6]);
			}
			// else
				// $fic.= "/";

			return $valeurs[1]."=\"".$valeurs[3].$valeurs[4]."/".$fic."\"";
		},$html);

		return $html;
	}
}

?>