

/**

	Fonctions appelées systématiquement
	
**/	
	$(document).ready(function()
	{
		$("button, input:button, input:submit, a.button").button();
	});
	
	
	function details(self)
	{
		var bloc = self.next(".details");
		if(bloc.hasClass("visible"))
			bloc.slideUp().removeClass("visible");
		else
			bloc.slideDown().addClass("visible");
	}
	
	function init_resultat_jeux(iPage,nbParPages,nbArticles)
	{
		var loadPage = function(pageDemandee, nbParPages)
		{
			var html = "";
			$.ajax({
			  type: 'POST',
			  url: site_root+"/ajax/resultat_cadavre.php",
			  data: {"iPage": pageDemandee, "nb" : nbParPages},
			  async:false
			}).done(function(msg)
			{
				retour = JSON.parse(msg);
				html = retour["html"];
			});
			
			return html;
		};
		
		var loadedPage = function()
		{
			$(this).find("button:not(.ui-button)").button();
			$(this).find(".details").removeClass("visible").hide();
		};
		
		$("#cadavre").pagination({
			"siteRoot" : site_root,
			"onLoadPage" : loadPage, 
			"onLoadedPage" : loadedPage, 
			"paginationDefaut" : nbParPages,
			"pageCourante" : iPage,
			"nbArticles" : nbArticles,
			"obj": "cadavres"
		}).pagination("load",iPage);
	}
	
	function init_articles(iPage,nbParPages,nbArticles,isConnecte)
	{	
		lastIds = {"theme": $("#theme").val() , "auteur" : $("#auteur").val()};
		var loadPage  = function(pageDemandee, nbParPages)
		{
			// var retourPage	 = new Array;
			var html = "";
			$.ajax({
			  type: 'POST',
			  url: site_root+"/ajax/articles.php",
			  data: {"iPage": pageDemandee, "nb" : nbParPages, "theme[]" : $("#theme").val(), "auteur[]" : $("#auteur").val(), "atelier[]" : $("#atelier").val()},
			  async:false
			}).done(function(msg)
			{
				retour = JSON.parse(msg);
				html = retour["html"];
			});
			
			return html;
		};
		
		var loadedPage = function()
		{
			var posterCom = function(commentaire, idTexte)
			{
				isEnvoye = false;
				$.ajax({
					type: 'POST',
					url : site_root + "/ajax/poster_commentaire.php",
					async: false,
					data: { "commentaire" : commentaire || "", "idTexte" : idTexte}				
				}).done(function(msg) 
				{
					var retour = JSON.parse(msg);
					isEnvoye = !retour["erreur"];
				});
				
				return isEnvoye;
			};
			
			$(this).find(".commentaires").commentaire({"posterCom" : posterCom});
		};
		
		$("#theme, #auteur, #atelier").each(function()
		{
			var self = $(this);
			
			self.multiselect({
				close: function()
				{

					var ids		 = self.val() || ["0"];
					var oldIds	 = lastIds[self.attr("id")] || ["0"];

					if(ids && oldIds && !($(oldIds).not(ids).length == 0 && $(ids).not(oldIds).length == 0))
					{
						self.parent("form").submit();
					}
				}
				// /*
				,click: function(event, ui)
				{	
					if(ui.value == 0 && ui.checked)
					{
						$(this).multiselect("uncheckAll");
						$(this).multiselect("widget").find(":checkbox:first").click();			
					}else if(ui.value != 0 && $(this).multiselect("widget").find(":checkbox:first").attr("checked"))
					{
						$(this).multiselect("widget").find(":checkbox:first").click();
						$(this).multiselect("widget").find(":checkbox[value="+ui.value+"]").click();
					}
				}
				// */
			});
		});
		$(".pagination").pagination({"siteRoot" : site_root,"onLoadedPage" : loadedPage, "onLoadPage" : loadPage, "paginationDefaut" : nbParPages, "pageCourante" : iPage, "nbArticles" : nbArticles}).pagination("load",iPage);
	}
	
	function modification(form)
	{
		$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"Veuillez patienter...\"/>");
		ajaxXML("compte.php",$(form).serialize(), ajax_modification);
		return false;
	}
	
	
	function inscription(form)
	{
		$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"Veuillez patienter...\"/>");
		ajaxXML("inscription.php",$(form).serialize(), ajax_inscription);
		return false;
	}
	
	function ajax_inscription(xml)
	{
		var erreur= getValeurOfStringNode(xml,"erreur");
		$("#feedback").html(getHTMLOfNode(xml,"msg"));
		if(!erreur)
		{
			document.location.href = site_root+"/pages/compte";
		}
	}
	
	function ajax_modification(xml)
	{
		var erreur= getValeurOfStringNode(xml,"erreur");
		$("#feedback").html(getHTMLOfNode(xml,"msg"));
	
	}
	
	
	function connection()
	{
		var login = $("#login").val();
		var mdp = $("#mdp").val();

		$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"Veuillez patienter...\"/>");
		ajaxXML("connection.php",{login : login , mdp : mdp}, ajax_connection);
		return false;
	}
	
	function ajax_connection(xml)
	{
		var erreur= getValeurOfStringNode(xml,"erreur");
		$("#feedback").html(getHTMLOfNode(xml,"msg"));
		if(!erreur)
		{
			document.location.href = site_root+"/pages/";
		}
	}
	

	
	function init_cadavre()
	{
		$("#newCadavre").unbind("click").click(function()
		{
			$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" > Veuillez patienter...");
			$.post(site_root+"/ajax/cadavre.php").done(function(data)
			{
				var retour = JSON.parse(data);
				$("#feedback").html(retour["msg"]);
				
				$.post(site_root+"/socket/serveur.php");
				
				if(!retour["erreur"])
					document.location.href = site_root+"/pages/ce/i/"+retour["id"];
			
			});
			return false;
		});
		
		$("#rejoindreCadavre").unbind("click").click(function()
		{
			$.post(site_root+"/socket/serveur.php");
			return true;
		});
	}
	
	
	function init_atelier()
	{
		$("#ajouter").unbind("click").click(function(e)
		{
			formulaireAtelier(1);
			return false;
		});
	}
	
		function voirNews(idNews)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/voirNews.php",
				{"idNews": idNews || null}
		).done(function( msg )
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons["Fermer"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
	}
	function modifierRetour(idRetour)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_retour.php",
				{"idRetour": idRetour || null}
		).done(function( msg )
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
	}
	
	function actionRetour(form)
	{
		$("#feedback2").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$(".ui-dialog-buttonset button").button("disable");
		
		var disabled = form.find(':disabled').removeAttr('disabled');
		var datas = form.serialize();
		disabled.attr('disabled','disabled');
		
		$.post(site_root+"/ajax/retour.php",
				datas
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			if(retour["erreur"])
			{
				$("#bd").dialog("option","title",retour["header"]);
				$("#feedback2").html(retour["msg"]);
				$(".ui-dialog-buttonset button").button("enable");
			}else
			{
				$("#bd").dialog("destroy");
				$("#bd").remove();
				$("#feedback").html(retour["msg"]);
				
				if(retour["mode"] == 1)
				{
				
				}else if(retour["mode"] == 2)
				{
					
				}else if(retour["mode"] == 3)
				{
					
				}
				
				setTimeout(function() { location.reload(); },2000);	
			}
		});
		return false;
	}
	
	function modifierTexte(idTexte)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_texte.php",
				{"idTexte": idTexte || null}
		).done(function( msg )
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
	}
	
	function actionTexte(form)
	{
		$("#feedback2").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$(".ui-dialog-buttonset button").button("disable");
		
		var disabled = form.find(':disabled').removeAttr('disabled');
		var datas = form.serialize();
		disabled.attr('disabled','disabled');
		
		$.post(site_root+"/ajax/texte.php",
				datas
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			if(retour["erreur"])
			{
				$("#bd").dialog("option","title",retour["header"]);
				$("#feedback2").html(retour["msg"]);
				$(".ui-dialog-buttonset button").button("enable");
			}else
			{
				$("#bd").dialog("destroy");
				$("#bd").remove();
				$("#feedback").html(retour["msg"]);
				
				if(retour["mode"] == 1)
				{
				
				}else if(retour["mode"] == 2)
				{
					
				}else if(retour["mode"] == 3)
				{
					
				}
				
				setTimeout(function() { location.reload(); },2000);	
			}
		});
		return false;
	}
	
	function modifierTheme(idTheme)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_theme.php",
				{"idTheme": idTheme || null}
		).done(function( msg )
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
	}	
	
	function actionTheme(form)
	{
		$("#feedback2").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$(".ui-dialog-buttonset button").button("disable");
		
		var disabled = form.find(':disabled').removeAttr('disabled');
		var datas = form.serialize();
		disabled.attr('disabled','disabled');
		
		$.post(site_root+"/ajax/theme.php",
				datas
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			if(retour["erreur"])
			{
				$("#bd").dialog("option","title",retour["header"]);
				$("#feedback2").html(retour["msg"]);
				$(".ui-dialog-buttonset button").button("enable");
			}else
			{
				$("#bd").dialog("destroy");
				$("#bd").remove();
				$("#feedback").html(retour["msg"]);
				
				setTimeout(function() { location.reload(); },2000);	
			}
		});
		return false;
	}
	
	function formulaireAtelier(mode, idAtelier)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_atelier.php",
				{"mode" : mode, "idAtelier": idAtelier || null}
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
	}
	
	
	function actionAtelier(form)
	{
		$("#feedback2").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$(".ui-dialog-buttonset button").button("disable");
		
		var disabled = form.find(':disabled').removeAttr('disabled');
		var datas = form.serialize();
		disabled.attr('disabled','disabled');
		
		$.post(site_root+"/ajax/atelier.php",
				datas
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			if(retour["erreur"])
			{
				$("#bd").dialog("option","title",retour["header"]);
				$("#feedback2").html(retour["msg"]);
				$(".ui-dialog-buttonset button").button("enable");
			}else
			{
				$("#bd").dialog("destroy");
				$("#bd").remove();
				$("#feedback").html(retour["msg"]);
				
				setTimeout(function() { location.reload(); },2000);	
			}
		});
		return false;
	}

	
	function modifierAtelier(idAtelier)
	{
		formulaireAtelier(2,idAtelier);
		return false;
	}

	function supprimerAtelier(idAtelier)
	{
		formulaireAtelier(3,idAtelier);
		return false;
	}
	
	function ajouterNews()
	{	
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_news.php"
				
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});	
		return false;
	}
	
	function modifierNews(idNews)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_news.php",
				{ "idNews": idNews || null}
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons[retour["boutton"]] =  function() {
					$("#bd form").submit();
				};
				bouttons["Annuler"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});
		
		return false;
	}
	
	function supprimerNews(idNews)
	{
		var bd = creerBD("Chargement en cours...", "<img src=\""+site_root+"/img/loading.gif\" /> Veuillez patienter",null,"500px");
		$.post(site_root+"/ajax/formulaire_news.php",
				{ "idNews2": idNews || null}
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			$("#bd").dialog("option","title",retour["header"]);
			$("#bd").html(retour["msg"]);
			
			if(!retour["erreur"])
			{
				var bouttons = {};
				bouttons["Fermer"] = function() {
					$("#bd").dialog("close");
				};
				
				$("#bd").dialog("option","buttons",bouttons);
			}
			
			$("#bd .datepicker").datepicker();
		});	
		
		return false;
	}

	function actionNews(form)
	{
		$("#feedback2").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$(".ui-dialog-buttonset button").button("disable");
		
		var disabled = form.find(':disabled').removeAttr('disabled');
		var datas = form.serialize();
		disabled.attr('disabled','disabled');
		
		$.post(site_root+"/ajax/news.php",
				datas
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			if(retour["erreur"])
			{
				$("#bd").dialog("option","title",retour["header"]);
				$("#feedback2").html(retour["msg"]);
				$(".ui-dialog-buttonset button").button("enable");
			}else
			{
				$("#bd").dialog("destroy");
				$("#bd").remove();
				$("#feedback").html(retour["msg"]);
				
				if(retour["mode"] == 1)
				{
				
				}else if(retour["mode"] == 2)
				{
					
				}else if(retour["mode"] == 3)
				{
					
				}
				
				setTimeout(function() { location.reload(); },2000);	
			}
		});
		return false;
	}
	
	function inscriptionAtelier(idAtelier)
	{
		$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$.post(site_root+"/ajax/inscriptionAtelier.php",
				{idAtelier : idAtelier}
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			
			if(retour["erreur"])
			{
				$("#feedback").html(retour["msg"]);
			}else
			{
				$("#feedback").html(retour["msg"]);
				$("#action"+retour["atelier"]).html("<a href=\"#desinscrire\" onClick=\"return desinscriptionAtelier('"+retour["atelier"]+"');\">"+
														"<img src=\""+site_root+"/img/supprimer2.png\"   alt=\"Se désinscrire\" title=\"Se désinscrire de cet atelier\"/>"+
													"</a>");
			}
		});
		
		return false;
	}

	function desinscriptionAtelier(idAtelier)
	{
		$("#feedback").html("<img src=\""+site_root+"/img/loading.gif\" alt=\"\" />Veuillez patienter ... ");
		$.post(site_root+"/ajax/desinscriptionAtelier.php",
				{idAtelier : idAtelier }
		).done(function( msg ) 
		{
			retour = JSON.parse(msg);
			
			if(retour["erreur"])
			{
				$("#feedback").html(retour["msg"]);
			}else
			{
				$("#feedback").html(retour["msg"]);
				$("#action"+retour["atelier"]).html("<a href=\"#inscrire\" onClick=\"return inscriptionAtelier('"+retour["atelier"]+"');\" >"+
														"<img src=\""+site_root+"/img/valider.png\" alt=\"S'inscrire\" title=\"S'inscrire à cet atelier\"/>"+
													"</a>");
			}
		});
		
		return false;
	}
	
/**

	Fonctions spécifiques aux pages
	
**/
	
	function init_publication()
	{
		CKEDITOR.replace('texte',{
			language : "fr"
		});
	}
	
	
	function init_inscription() // Page inscription.php
	{
		infobulle("#aidelogin","Le nom doit comporter au moins 3 caractères, ne doit pas contenir de caractères spéciaux et ne doit pas être déjà utilisé");
		infobulle("#aidepwd","Le mot de passe doit comporter au moins 6 caractères et ne doit pas contenir de caractères spéciaux");
		infobulle("#aidemail","L'adresse mail doit comporter au moins 6 caractères et ne doit pas contenir de caractères spéciaux.<br /><b>Note:</b> Votre adresse mail ne sera pas utilisée à des fins commerciales");
	}
	
	
	function send_texte()
	{
		var texte = CKEDITOR.instances.texte.getData();
		var titre = $("#titre").val();
		var theme = $("#theme").val();
		var atelier=$("#atelier").val();
		var brouillon = document.getElementById("brouillon").checked;
		var privacy = document.getElementById("privacy").checked;
		if(titre != "" && theme != "0" && texte != "")
		{
			$.post(
				site_root+"/ajax/texte.php",
				{"titre" : titre , "theme" : theme, "texte" : texte, "brouillon" : brouillon, "atelier" : atelier, "privacy" : privacy}
			).done(function(msg)
			{
				var retour = JSON.parse(msg);
				$("#feedback").html(retour["msg"]);
			});
		}else
		{
			alert("Un des champs obligatoire n'est pas rempli");
		}		
		return false;
	}
	
	function fill_texte()
	{
		var titre = $("#titres").val();
		if (titre != "0")
		{
			ajaxXML("load.php",{"titre" : titre}, fill_texte2);
		}
		else
		{
			alert("Choisir le texte voulu!");
		}
		return false;
	}

	function shuffle(a)
	{
	   var j = 0;
	   var valI = '';
	   var valJ = valI;
	   var l = a.length - 1;
	   while(l > -1)
	   {
			j = Math.floor(Math.random() * l);
			valI = a[l];
			valJ = a[j];
			a[l] = valJ;
			a[j] = valI;
			l = l - 1;
		}
		return a;
	 }
	 
	 
	 function send_melanger()
	{
		var texteOriginal = CKEDITOR.instances.texte.getData();
		var texteMelange = document.getElementsByName('texteMelange')[0].innerHTML;
		
		if( texteOriginal != "" && texteMelange != "")
		{
			ajaxXML("melange.php",{"texteOriginal" : texteOriginal, "texteMelange" : texteMelange}, ajax_texte);
		}else
		{
			alert("Un des champs obligatoires n'est pas rempli");
		}
		
		return false;
	}


	function melangerTexte()
	{
		var regex = /(<([^>]+)>)/ig;
		var texte = CKEDITOR.instances.texte.getData().replace(regex,"");
		var reg=new RegExp("[ ,!:,?()]+", "g");
		var tableau=texte.split(reg);
		var tableauMelange = shuffle(tableau);
		var texteMelange = '';
		for(var i=0;i<tableauMelange.length;i++)
		{
			texteMelange+=tableauMelange[i] + ' ';
		}
		//CKEDITOR.instances.texte.setData(texteMelange);
		document.getElementById('texteMelange').innerHTML=texteMelange;
	}
	

	function fill_texte2(xml)
	{
		var titre = getValeurOfStringNode(xml,"titre");
		document.getElementById("titre").value = titre;
		var contenu = getHTMLOfNode(xml,"contenu");
		CKEDITOR.instances.texte.insertHtml(contenu);
		var theme = getValeurOfStringNode(xml,"theme");
		$("#theme").val(theme);
		var privacy = getValeurOfStringNode(xml,"privacy");
		if (privacy == '0')
		{
			document.getElementById("privacy").checked = true;
		}
		else
		{
			document.getElementById("privacy").checked = false;
		}
		
		
	}
	
	
	function init_modif() // Page compte.php
	{
		infobulle("#aidelogin","Le nom doit comporter au moins 3 caractères, ne doit pas contenir de caractères spéciaux et ne doit pas être déjà utilisé");
		infobulle("#aidepwd","Le mot de passe doit comporter au moins 6 caractères et ne doit pas contenir de caractères spéciaux");
		infobulle("#aidemail","L'adresse mail doit comporter au moins 6 caractères et ne doit pas contenir de caractères spéciaux.<br /><b>Note:</b> Votre adresse mail ne sera pas utilisée à des fins commerciales");
	}
/**

	Fonctions générales
	
**/

	
	
	
	function infobulle(id,str,position)
	{
		$().ready(function() 
		{
			$(id).qtip({
			   content: str,
			   style: { 
				  width: 350,
				  background: '#10729C',
				  color: 'black',
				  textAlign: 'center',
				  border: {
					 width: 7,
					 radius: 5,
					 color: '#10729C'
				  },
				  tip: (position || 'topLeft'),
				}
			   });
		});
	}
	
	
	/*
		creerBD : Ouvre une boîte de dialogue jQuery UI et retourne l'objet jQuery
					/!\ La boîte de dialogue possède un conteneur possédant l'id "feedback", pour le retour des communications ajax /!\
					
					- titre 		: Titre de la boîte de dialogue
					- corps 		: Contenu du texte de la boîte de dialogue
					- bouttons 		: Bouton OK par défaut, tableau associatif de type  { "nomBoutton" : function() { alert("nomBoutton cliqué"); } }
					- defautWidth 	: "Auto" par défaut, spécifie la largeur de la boîte de dialogue ( ex: "500px")
					- isModale 		: True par défaut, rend la boîte de dialogue non modale si passé à false
					- onClose 		: Null par défaut, fonction associée à l'évènement de fermeture de la fenêtre
	*/
	function creerBD(titre,corps,bouttons,defautWidth, isModale, onClose)
	{
		jQuery("<div>", 
		{
			id: "bd",
		}).appendTo("body");
		
		isModale = !(typeof isModale=="null" || (typeof isModale!="null" && isModale == false));

		var bd = $('#bd').html("<div id=\"feedback\" class=\"center\"></div>" + (corps || '')).dialog(
		{ 	autoOpen: false, 
			title: titre ||  'iScore',
			modal: isModale,
			width : defautWidth || "auto",
			buttons: bouttons || {
				"OK": function() {
				  $( this ).dialog( "close" );
				}
			},
			close: (onClose || function(event, ui) { $(this).remove(); } )
		}).dialog('open');
			
		return bd;
	}

/**

	Fonctions générales d'ajax par XML
	
**/

	
	
	
	function ajaxXML(page, params, functionCallBack)
	{
		var xhr = getXhr();
        xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{   
				var rst = ParseHTTPResponse (xhr);
				if(typeof functionCallBack == "function")
				{
					var xml =rst.getElementsByTagName('news');
					if(xml.length > 0)
					{
						functionCallBack.call(this,xml);
					}
				}
			}
		};
				
		var str = "";
		if(typeof params == "object") // Si tableau style { key : value} ou tableau[key] = value
		{
			for (var key in params)
				str += (str == "" ? "" : "&") + key + "=" + params[key];
		}else if(typeof params == "string")
			str = params;
			
		xhr.open("POST",site_root+"/ajax/" + page,true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send(str);    
	}

	function getXhr()
	{
		var xhr = null; 
		if(window.XMLHttpRequest) 
			xhr = new XMLHttpRequest(); 
		else if(window.ActiveXObject)
		{ 
			try
			{
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e)
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}else
		{ 
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
			xhr = false; 
		} 
		return xhr;
	}
	
	function ParseHTTPResponse(httpRequest)
	{
		var xmlDoc=httpRequest.responseXML;
		if(!xmlDoc||!xmlDoc.documentElement)
		{
			if(window.DOMParser)
			{
				var parser = new DOMParser();
				 try
				 {
					xmlDoc=parser.parseFromString(httpRequest.responseText, "text/xml");
				 }catch (e){};
			}else
			{
				xmlDoc=CreateMSXMLDocumentObject();
				if(!xmlDoc)
					return null;
				xmlDoc.loadXML (httpRequest.responseText);
			}
		}
		var errorMsg = null;
		if(xmlDoc.parseError && xmlDoc.parseError.errorCode!=0){
			errorMsg = "XML Parsing Error: " + xmlDoc.parseError.reason
			+ " at line " + xmlDoc.parseError.line
			+ " at position " + xmlDoc.parseError.linepos;
		}else
		{
			if(xmlDoc.documentElement)
			{
				if(xmlDoc.documentElement.nodeName == "parsererror")
				{
					 errorMsg = xmlDoc.documentElement.childNodes[0].nodeValue;
				}
			}
		}
		if(errorMsg)
		{
			alert (errorMsg);
			return null;
		}
		return xmlDoc;
	}
	
	function getValeurOfStringNode(parent,enfant,index)
	{
		if(parent[index || 0].getElementsByTagName(enfant)[0].childNodes.length > 0)
		{
			var retour = parent[index|| 0].getElementsByTagName(enfant)[0].firstChild.nodeValue;
		}else
		{
			var retour = "";
		}		
		return retour;
	}
	
		
	function getValeurOfIntNode(parent,enfant)
	{
		if(parent[0].getElementsByTagName(enfant)[0].childNodes.length > 0)
		{
			var retour = parent[0].getElementsByTagName(enfant)[0].firstChild.nodeValue;
		}else
		{
			var retour = 0;
		}
		
		return retour;
	}
	
	function getHTMLOfNode(parent,enfant,index)
	{
		if(parent[index || 0].getElementsByTagName(enfant)[0].childNodes.length > 0)
		{
			var retour = parent[index || 0].getElementsByTagName(enfant)[0].firstChild.textContent;
		}else
		{
			var retour = "";
		}
		
		return retour;
	}