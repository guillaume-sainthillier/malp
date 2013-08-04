(function($)
{
    $.fn.commentaire=function(options)
    {
		var defauts=
		{
			"nbCom" : 5,
			"connecte" : true,
			"posterCom" : null
		};  
		
		var parametres=$.extend(defauts,options,options, options);

		function slider(objet,oldPage,newPage) 
		{
			if(oldPage != newPage)
			{
				objet.parent().parent().parent().find(" .visible").hide("slide", { direction: (newPage > oldPage ? "left":"right")}, 400,function() {
							objet.parent().parent().parent().find(" .pages"+newPage).show("slide", { direction: (newPage < oldPage ? "left":"right")}, 400).addClass("visible");
					
				}).removeClass("visible");
			}
		}
		
		function getHTMLBM(texte)
		{
			return '<div style="font-size:1.0em;padding:5px;" class="message ui-widget ui-corner-all ui-state-highlight ui-state-error">'
			+'<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert">'
			+'</span>'
			+'<span >'+texte+'</span>'
			+'</div>';
		}
		
		function getHTMLOK(texte)
		{
			return '<div style="position:relative;padding:5px;" class="message ui-widget ui-corner-all ui-state-highlight ui-state-valid">'
			+'<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info">'
			+'</span>'
			+'<span class="text">'+texte+'</span>'
			+'</div>';
		}
		
		return this.each(function()
		{
			var uniqid = $(this).attr("id");
			$(this).children(":not(.commentaire, .pageindex)").remove();
			$(this).children(".pageindex").find(".commentaire").appendTo($(this));
			$(this).children(":not(.commentaire)").remove();
			// console.log(this);
			// return;
			$(this).addClass("blockCom");
			$(this).find(" .commentaire").addClass("ui-corner-all ui-state-default");
			var commentaires = $(this).find(" .commentaire"); // [<div commentaire>]
			var texte = "";
			if(parametres.connecte)
			{
				texte = "Commenter";
				$(this).prepend("<div class=\"newCom ui-corner-all actif ui-state-default\"><span class=\"fleft ui-icon ui-icon-comment\"></span>"+texte+"</div>");
				$(this).find(" .newCom").click(function() {
					$(this).parent().find(".visible").slideDown(400,function() {
						$(this).parent().find(".navigationCommentaire").slideDown("normal");
					});
					
					$(this).parent().find(" .nbComs").removeClass("actif");
					$(this).parent().find(" .reponse").remove();
					if($(this).parent().find(" .visible").find(" .posterCom").length == 0)
					{
						$(this).parent().find(" .visible").prepend("<div class=\"commentaire ui-corner-all ui-state-default\">"
																	+"<textarea class=\"contenuCom\" rows=\"3\" cols=\"60\"></textarea>"
																	+"<div class=\"fright quitterPost ui-widget ui-helper-clearfix\"><div class=\"ui-state-default ui-corner-all\" title=\"Fermer la fenêtre\"><span class=\"ui-icon ui-icon-closethick\"></span></div></div>"
																	+"<div class=\"posterCom ui-corner-all ui-state-default\" ><span class=\"fleft ui-icon ui-icon-mail-closed\"></span>Valider</div>"
																	+"<div class=\"retourCom\"></div>"
																	+"</div>");
						

						$(this).parent().find(" .quitterPost").click(function() {
							$(this).parent().remove();
						});
						$(this).parent().find(" .posterCom").click(function() {

							if(typeof parametres.posterCom == "function")
							{
								if(parametres.posterCom($(this).parent().find(" .contenuCom").val() || "",uniqid))// Le post du commentaire a marché
								{
									$(this).parent().parent().prepend("<div class=\"commentaire reponse\">"+getHTMLOK("Votre commentaire a bien été pris en compte")+"</div>");
									$(this).parent().remove();
								}else // Le post du commentaire a échoué
								{			
									$(this).parent().find(" .retourCom").html(getHTMLBM("Le post de votre message a échoué"));
								}
							}
						});
					}					
				});
				
			}
			texte = (commentaires.length == 0 ? "Aucun commentaire": commentaires.length + " commentaire" + (commentaires.length > 1 ? "s": ""));
			$(this).prepend("<div class=\"nbComs ui-corner-all actif ui-state-default\"><span class=\"fleft ui-icon ui-icon-comment\"></span>"+texte+"</div>");

			$(this).find(" .nbComs").click(function() {
				if($(this).hasClass("actif"))
				{
					$(this).parent().find(".visible").slideDown(400,function() {
						$(this).parent().find(".navigationCommentaire").slideDown("normal");
					});
					
					$(this).removeClass("actif");
				}else
				{
					$(this).parent().find(".navigationCommentaire").slideUp(400,function() {
						$(this).parent().find(".visible").slideUp("normal");
					});
					$(this).addClass("actif");
				}
				return false;
			});
			
			/* Arbo : <div commentaires>
						<div commentaire>
							<span pseudo></span>
							<span date></span>
							<div contenu></div>
						</div>
					</div>
			*/
			pageCourante = 0;
			lastPage = null;
			
			commentaires.each(function(i) // ieme <div commentaire>
			{
				var i = parseInt(i,10);
				var isNewPage = (i % parametres.nbCom == 0);
				if(isNewPage)
				{
					lastPage = $(this).wrap('<div class="pageindex pages'+pageCourante+'"></div>').parent();
				}else
				{
					$(this).appendTo(lastPage);
				}
				
				$(this).addClass( (i % 2 == 0 )? "oddleft":"evenright");
				if(isNewPage)
				{
					if(pageCourante == 0)
						lastPage.addClass("visible");

					pageCourante++;
				}
			});
			
			if($(this).find(" .pageindex").length == 0)
			{
				$(this).append("<div class=\"pageindex pages0 visible\"></div>");
			}
			
			nbPages = pageCourante;
			
			$(this).find(" .pageindex").hide();
			
			
			var str = "<div class=\"navigationCommentaire ui-buttonset ui-buttonset-multi \"><ul class=\"ui-widget ui-helper-clearfix  ui-button ui-state-default\"><li class=\"prec ui-corner-left\">< Précédent</li>";
			for(var j = 0; j < nbPages;j++)
				str += "<li "+(j == 0 ? "class=\"active\"":"")+"><a href=\"#page-"+j+"\">"+(j+1)+"</a></li>";
			str += "<li class=\"suiv ui-corner-right\">Suivant ></li></ul></div>";
			
			$(this).append(str);
			$(this).find(" .navigationCommentaire").hide();
			$(this).find(" .navigationCommentaire ul").addClass("ui-corner-all");
			
			$(this).find(" .navigationCommentaire .prec").addClass("ui-state-disabled");
			
			$(this).find(".navigationCommentaire li").click(function() {
				var oldNoeud = $(this).parent().find(" li.active");
				var aAncienEnfant = oldNoeud.children().attr("href");
				var aEnfant = $(this).children().attr("href");
				var exp = /^#page-[0-9]$/;
				
				
				var newPage = -1;
				var oldPage = -1;
				var tmp;
				if(exp.test(aEnfant))
				{
					tmp = aEnfant.split("-");
					newPage = parseInt(tmp[1],10);
				}
				if(exp.test(aAncienEnfant))
				{
					tmp = aAncienEnfant.split("-");
					oldPage = parseInt(tmp[1],10);					
				}
				

				if($(this).hasClass("prec") && !isNaN(oldPage) && oldPage > 0 && oldPage < nbPages)
				{
					newPage = oldPage - 1;
					slider($(this),oldPage,newPage);
					oldNoeud.removeClass("active");
					$(this).parent().find('li a[href="#page-'+newPage+'"]').parent().addClass("active");
					
				}else if($(this).hasClass("suiv") && !isNaN(oldPage) && oldPage >= 0 && oldPage < (nbPages-1))
				{
					newPage = oldPage + 1;
					slider($(this),oldPage,newPage);
					oldNoeud.removeClass("active");
					$(this).parent().find('li a[href="#page-'+newPage+'"]').parent().addClass("active");
				}else if(newPage >= 0 && oldPage >= 0)
				{
					slider($(this),oldPage,newPage);
					oldNoeud.removeClass("active");
					$(this).addClass("active");
				}
				
				
				if(newPage == 0) 
					$(this).parent().find(" .prec").addClass("ui-state-disabled");
				else if(newPage > 0)
					$(this).parent().find(" .prec").removeClass("ui-state-disabled");
					
				if(newPage == (nbPages-1)) 
					$(this).parent().find(" .suiv").addClass("ui-state-disabled");
				else if(newPage > 0)
					$(this).parent().find(" .suiv").removeClass("ui-state-disabled");
				
				return false;
			});
			
			if(nbPages < 2)
				$(this).find(".navigationCommentaire .suiv").addClass("ui-state-disabled");
				
			$(this).find(".newCom, .nbComs").hover(function() { $(this).addClass("ui-state-hover").removeClass("ui-state-default");},function() { $(this).addClass("ui-state-default").removeClass("ui-state-hover");});;
		});
    };
})(jQuery);