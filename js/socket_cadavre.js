
idJeu	 = null;
idUser	 = null;
server	 = null;
myId	 = null;

$(document).ready(function()
{
	var ws = "ws://"+document.location.host+":9300";
	server = new FancyWebSocket(ws);
	server.connect();
	
	server.bind('open', function() {
		$(".frameCadavre .actions, .frameCadavre .membres, .frameCadavre .jeu , .frameCadavre .texteSaisi").show("normal");
		$("#logs").html("Connecté au serveur<br />");
		
		jeu = {"idJeu": idJeu, "idUser": idUser};
		server.send("join",jeu);
	});

			
	server.bind('close', function( data ) {
		$(".frameCadavre .actions, .frameCadavre .membres, .frameCadavre .jeu , .frameCadavre .texteSaisi, .frameCadavre .chat").hide("normal");
		log("Déconnecté du serveur");
	});

	
	server.bind('message', function( payload ) {
		var reponse = JSON.parse(payload);
		switch(reponse.action.toLowerCase())
		{
			case "last_phrase" 		: last_phrase(JSON.parse(reponse.data)); 	break;
			case "load_chat" 		: load_chat(JSON.parse(reponse.data)); 		break;
			case "stop_chat" 		: stop_chat(JSON.parse(reponse.data)); 		break;
			case "chat" 			: chat(JSON.parse(reponse.data)); 			break;
			case "nobody" 			: nobody(reponse.data);				 		break;
			case "get_my_id" 		: get_my_id(JSON.parse(reponse.data)); 		break;
			case "move_to_spec" 	: move_to_spec(JSON.parse(reponse.data)); 	break;
			case "move_to_player" 	: move_to_player(JSON.parse(reponse.data));	break;
			case "admin" 			: admin(JSON.parse(reponse.data)); 			break;
			case "jec" 				: jec(JSON.parse(reponse.data)); 			break;
			case "list" 			: list(JSON.parse(reponse.data)); 			break;
			case "join" 			: join(JSON.parse(reponse.data)); 			break;
			case "leave" 			: leave(JSON.parse(reponse.data)); 			break;
			case "resultat" 		: resultat(JSON.parse(reponse.data)); 		break;
			case "error" 			: error(reponse.data); 						break;
			case "close_game" 		: close_game(JSON.parse(reponse.data)); 	break;
			default					: error(reponse.action+" non reconnu");		break;
		}
	});

});

/**


	Fonctions appelées par le serveur


**/

//Affiche la dernière phrase du joueur
function last_phrase(membre)
{
	$(".frameCadavre .jeu .phrase").html("<b>"+ (membre["id"] == myId ? "Vous</b> avez" : membre["nom"]+"</b> a") + " écrit: "+ membre["phrase"]+ "<br />");
}

//Démarre le salon de chat
function load_chat(membre)
{
	log("<b>"+(membre["id"] == myId ? "Vous</b> avez" : membre["nom"]+"</b> a")+" démarré le salon de chat");
	$(".frameCadavre .chat").slideDown("slow");
}

//Arrête le salon de chat
function stop_chat(membre)
{
	log("<b>"+(membre["id"] == myId ? "Vous</b> avez" : membre["nom"]+"</b> a")+"</b> a arrêté le salon de chat");
	$(".frameCadavre .chat").slideUp("slow");
}

//Ajoute un message dans le chat
function chat(msg)
{
	$(".frameCadavre .chatTexte").prepend(msg + "<br />");
	$(".frameCadavre .chatTexte").animate({ scrollTop: 0}, "slow");	
}

//Fonction appelée lorsqu'il n'y a aucun joueur qui joue
function nobody(membre)
{
	$("#sendTexte, #passerTour").button("disable");
	$("#texte").attr("disabled","disabled");
}

//Assigne l'id attribué par le serveur
function get_my_id(id)
{
	myId = id;
}

//Signale qu'un membre est passé spectateur
function move_to_spec(membre)
{
	log("<b>"+membre["nom"]+"</b> est passé spectateur");
	$("#membre"+membre["id"]).remove();
	$("#nbSpec").siblings("ul").append("<li id=\"membre"+membre["id"]+"\">"+membre["nom"]+"</li>");
	majCurrentUser();
	majNbMembres(null,true);
}

//Signale qu'un membre n'est plus spectateur
function move_to_player(membre)
{
	log("<b>"+membre["nom"]+"</b> a rejoint la partie");
	$("#membre"+membre["id"]).remove();
	$("#nbMembres").siblings("ul").append("<li id=\"membre"+membre["id"]+"\">"+membre["nom"]+"</li>");
	majCurrentUser();
	majNbMembres(null,true);
}

//Passage du simple user à l'administrateur de la partie
function admin(admin)
{
	$("#closeGame, #loadChat").show();
	log("Vous êtes désormais l'administrateur de la partie");
}

//Indique le membre qui doit jouer
function jec(membre)
{
	$(".frameCadavre .phrase").html("");	
	$(".frameCadavre .membres").find("li.currentTurn").removeClass("currentTurn");
	$(".frameCadavre #membre"+membre["id"]).addClass("currentTurn");
	
	if(membre["id"] == myId)
	{
		log("C'est à <b>vous</b> de jouer");
		$("#sendTexte, #passerTour").button("enable");
		$("#texte").attr("disabled",false);
	}else
	{
		log("C'est à <b>"+membre["nom"]+"</b> de jouer");
		$("#sendTexte, #passerTour").button("disable");
		$("#texte").attr("disabled","disabled");
	}
}

//Affiche la liste des joueurs connectés
function list(membres)
{
	var ul = $("#nbMembres").siblings("ul").html("");
	var ul2 = $("#nbSpec").siblings("ul").html("");
	for(var i = 0; i < membres.length; i++)
	{
		membres[i] = JSON.parse(membres[i]);
		if(membres[i]["inGame"])
			ul.append("<li id=\"membre"+membres[i]["id"]+"\">"+membres[i]["nom"]+"</li>");
		else
			ul2.append("<li id=\"membre"+membres[i]["id"]+"\">"+membres[i]["nom"]+"</li>");
	}
	
	majNbMembres();
	majCurrentUser();
}

//Affiche le nouveau membre connecté
function join(membre)
{
	$("#" + (membre["inGame"] ? "nbMembres" : "nbSpec")).siblings("ul").append("<li id=\"membre"+membre["id"]+"\">"+membre["nom"]+"</li>");
	log("<b>" +membre["nom"] + "</b> a rejoint la partie");
	majNbMembres(false);
}

//Affiche le nouveau membre déconnecté
function leave(membre)
{
	var li = $("#membre"+membre["id"]);
	log("<b>" + li.text() + "</b> a quitté la partie");
	li.remove();
	majNbMembres(!membre["inGame"]);
}

//Met fin au jeu et affiche le détail de la partie
function close_game(resultat)
{
	log("L'administrateur a terminé la partie");
	var ul = $("<ul>");
	var membres = resultat["details"];

	for(var i = 0; i < $(membres).length; i++)
	{
		ul.append($("<li>").html("<b>" + membres[i]["nom"]+"</b>: " + membres[i]["phrase"]));
	}
	
	var boutton = $("<button>Détails</button>");
	
	var div = $("<div>").append("<br />").append(ul);
	
	$(".frameCadavre .actions, .frameCadavre .membres, .frameCadavre .jeu,"+
		".frameCadavre .texteSaisi, .frameCadavre .chat").slideUp("slow");
	$(".frameCadavre .resultat").slideUp("slow",function(){
	
		$(this).append("Résultat du jeu: <br />"+resultat["phraseFinale"]+" <br /><br />");		
		$(this).append(boutton).append(div);
		
		$(div).hide();
		$(this).find("a").button();
		
		$(this).slideDown("slow").find("button").button().click(function(e){
			if($(this).hasClass("ouvert"))
			{
				$(this).next().slideUp("slow");
				$(this).removeClass("ouvert");
			}else
			{
				$(this).next().slideDown("slow");
				$(this).addClass("ouvert");
			}
		});
	});
}

//Affiche les erreurs provenant du serveur
function error(msg)
{
	log(msg);
}

/**





**/

//Envoie un message dans le chat
function send_chat()
{
	var data = {"msg" : $(".frameCadavre .sendChat").val()};
	server.send("chat",data);
	
	$(".frameCadavre .sendChat").val("");
}

//Envoie la phrase au serveur
function envoyer_msg()
{
	var data = {"msg" : $(".frameCadavre #texte").val()};
	server.send("phrase",data);
	
	$(".frameCadavre #texte").val("");
}

//Initialise la frameCadavre
function init_jeu_cadavre(user,jeu)
{
	idJeu = jeu;
	idUser = user;
	$(".frameCadavre .actions, .frameCadavre .membres, .frameCadavre .jeu , .frameCadavre .texteSaisi, .frameCadavre .chat, .frameCadavre .resultat").hide();
	
	$("#closeGame, #movetoplayer, #closeChat, #loadChat").hide();
	
	
	$("#sendTexte, #passerTour").button("disable");
	$("#texte").attr("disabled","disabled");
	
	$("#closeGame").click(function(e)
	{
		server.send("close_game","");
	});
	
	$("#movetoplayer").click(function(e)
	{
		server.send("move_to_player","");
		$(this).hide();
		$("#movetospec").show();
	});
	
	$("#movetospec").click(function(e)
	{
		server.send("move_to_spec","");
		$(this).hide();
		$("#movetoplayer").show();
	});
	
	$("#passerTour").click(function(e)
	{
		server.send("passer","");
	});
	
	$("#loadChat").click(function(e)
	{
		server.send("load_chat","");
		$(this).hide();
		$("#closeChat").show();
	});
	
	$("#closeChat").click(function(e)
	{
		server.send("close_chat","");
		$(this).hide();
		$("#loadChat").show();
	});
}

//Affiche une information au client
function log(msg)
{
	$("#logs").append(msg + "<br />");
	$("#logs").animate({ scrollTop: $("#logs")[0].scrollHeight}, "slow");	
}

function majNbMembres(specAndMembre, withoutAnim)
{
	if(specAndMembre == null || specAndMembre == false)
	{ 
		var nbMembres = $("#nbMembres").siblings("ul").find("li").length;
		if(withoutAnim)
		{
			$("#nbMembres").html(""+nbMembres);
			$("#nbMembres").next().html(" Joueur" + (nbMembres > 1 ? "s":""));
		}else
		{
			$("#nbMembres").fadeOut("slow",function()
			{
				$(this).html(""+nbMembres);
				$(this).next().html(" Joueur" + (nbMembres > 1 ? "s":""));
				$(this).fadeIn("slow");
			});
		}	
	}
	
	if(specAndMembre == null || specAndMembre == true)
	{
		var nbSpec = $("#nbSpec").siblings("ul").find("li").length;
		$("#nbSpec").html(""+nbSpec);
		if(withoutAnim)
		{
			$("#nbSpec").html(""+nbSpec);
			$("#nbSpec").next().html(" Spectateur" + (nbSpec > 1 ? "s":""));
		}else
		{
			$("#nbSpec").fadeOut("slow",function()
			{
				$(this).html(""+nbSpec);
				$(this).next().html(" Spectateur" + (nbSpec > 1 ? "s":""));
				$(this).fadeIn("slow");
			});
		}	
	}
}	

function majCurrentUser()
{
	$(".frameCadavre .currentUser").removeClass("currentUser");
	$(".frameCadavre #membre"+myId).addClass("currentUser");
}


