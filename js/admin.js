/**


	Fonctions liées à l'administration


**/


	function init_admin()
	{
		$("#contenu table").dataTable(
		{
			"sPaginationType": "full_numbers",
			"oLanguage": 
			{
            "sLengthMenu": "Afficher _MENU_ lignes par page",
            "sZeroRecords": "Aucune entrée trouvée",
            "sInfo": "",
            "sInfoEmpty": "Aucune ligne affichée",
            "sInfoFiltered": "(fitré de _MAX_ lignes)",
			"sSearch" : "Rechercher:",
			}
			
		});
	}

	function ajax_admin(xml)
	{
		var erreur	= getValeurOfStringNode(xml,"erreur");
		var reload	= getValeurOfStringNode(xml,"reload");
		$("#feedback").html(getHTMLOfNode(xml,"msg"));
		if(!erreur && reload)
		{
			setTimeout(function() { location.reload(); },1000);	
		}
	}
	
	function promouvoir(form)
	{
		$("#feedback").remove();
		$(".tab-tab").each(function()
		{	
			if($(this).find(form).length > 0)			
				$(this).before("<div id=\"feedback\"></div>");

		});
		$("#feedback").html("<img src=\"../img/loading.gif\" alt=\"Veuillez patienter...\"/>");
		ajaxXML("admin.php",$(form).serialize(), ajax_admin);
		return false;
	}
	
	function majFeedback()
	{
	
	}