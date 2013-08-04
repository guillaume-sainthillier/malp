(function($)
{

    var methods = 
	{
        init : function(optionss) 
		{
			return this.each(function()
			{
				options = $.extend( {}, options, optionss);
				var self = this;

				options.nbPages = Math.ceil(options.nbArticles / options.paginationDefaut);
				
				if(options.nbPages <= 0)
					options.nbPages = 1;
					
				if(options.pageCourante > options.nbPages)
					options.pageCourante = options.nbPages;
					
				var optionsSelect = "";
				for(var i = 1; i <= options.nbPages; i++)
				{
					optionsSelect += "<option value=\""+i+"\" "+(options.pageCourante == i ? "selected=\"selected\"" : "")+">"+i+"</option>";
				}
				
				var optionsNbPages = "";
				for(var i = 0; i < options.pagination.length; i++)
				{
					optionsNbPages += "<option value=\""+(options.pagination[i])+"\" "+(options.pagination[i] == options.paginationDefaut ? "selected=\"selected\"" : "")+">"+(options.pagination[i])+"</option>";
				}
				
				var divOptions = "<div class=\"center options\">" +
									"<div class=\"fleft prec pointeur\"><img src=\""+options.siteRoot+"/img/prev.png\" alt=\"\" />Précédent</div>"+
									"Page <select class=\"changePage\">"+optionsSelect+"</select> sur "+ options.nbPages +
									" &nbsp;&nbsp;Afficher <select class=\"changeNbPage\">"+optionsNbPages+"</select> "+options.obj+" par page" +
									"<div class=\"fright suiv pointeur\">Suivant <img src=\""+options.siteRoot+"/img/next.png\" alt=\"\" /></div>"+
									"<br /><div class=\"feedback\"></div>" +
								"</div>" +
								"<br /><br /><br /><div class=\"display\"></div>"+
								"<div class=\"loaded\"></div>";
				$(this).html(divOptions);
				
				$(this).find(".loaded").hide();
				
				$(this).find(".options .changePage").change(function()
				{
					return $(self).pagination("load",parseInt($(this).val(),10));
				});
				
				$(this).find(".options .changeNbPage").change(function()
				{
					options.paginationDefaut = parseInt($(this).val(),10);
					options.pageCourante = 1;
					return $(self).pagination(options).pagination("load",options.pageCourante);
				});
				
				$(this).find(".suiv").click(function()
				{
					$(self).pagination("load",options.pageCourante+1);
					return false;
				});	
				
				$(this).find(".prec").click(function()
				{
					$(self).pagination("load",options.pageCourante-1);
					return false;
				});	
				
				$(self).pagination("refreshNav");
			});
        },
		
		refreshNav : function()
		{
			return this.each(function()
			{
				var prec = $(this).find(".options .prec");
				var suiv = $(this).find(".options .suiv");
			
				if(options.pageCourante <= 1)
					prec.hide();
				else 
					prec.show();
					
				if(options.pageCourante >= options.nbPages)
					suiv.hide();
				else
					suiv.show();
			});
		},
		
        load : function( page ) 
		{
			return this.each(function()
			{
				var self = $(this);
				var iPage = page || options.pageCourante;
				if($(this).find(".display #"+options.obj+iPage).length == 0) //Si la page demandée n'est pas déjà affichée à l'écran
				{
					$(this).find(".feedback").html("<img src=\""+options.siteRoot+"/img/loading.gif\" alt=\"Veuillez patienter...\"/> Veuillez patienter...");
					if($(this).find(".loaded #"+options.obj+iPage).length == 0 ) //Si page non chargée
					{
						if(typeof options.onLoadPage == "function")
						{							
							var html = options.onLoadPage.apply(self,[iPage,options.paginationDefaut]);
							if(html == "")
								html = "Aucun "+options.obj;
							
							var newDiv = "<div id=\""+options.obj+iPage+"\">"+html+"</div>";
							$(this).find(".loaded").append(newDiv);
							$(this).pagination("displayPage",iPage,true);							
						}
					}// Sinon on l'affiche directement
					else
						$(this).pagination("displayPage",iPage,true);
						
					options.pageCourante = iPage;
					$(this).find(".options .changePage").val(iPage);
					$(this).pagination("refreshNav");
					
				}
			});
		},
		
		displayPage : function(newPage,isNew)
		{
			var self = $(this);
			var newHtml = $(this).find(".loaded #"+options.obj+newPage);
			if(newHtml.length > 0 )
			{
				var html = $(this).find(".display").html();
				$(this).find(".loaded").append(html);
				$(this).find(".display").slideUp("slow",function()
				{
					$(this).html(newHtml);
					self.find(".feedback").html("");
					if(isNew && typeof options.onLoadedPage == "function")
						options.onLoadedPage.apply(this);
					$(this).slideDown("normal");
				});
			}
		}
    };

    $.fn.pagination = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            return methods.init.apply( this, arguments );
        }   
    };
	
	var options = 
	{
		"paginationDefaut"	: 2,
		"onLoadPage"		: null,
		"onLoadedPage"		: null,
		"obj"				: "articles",
		"pageCourante" 		: 1,
		"nbPages"			: 2,
		"nbArticles"		: 2,
		"siteRoot"			: "../",
		"pagination"		: [1,2,5,10]
	};

})( jQuery );