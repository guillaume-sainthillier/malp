<?php

require_once "../class/fen.class.php";

$f = new fen("Devenez adhérent",0);

$f->displayHeader();

?>

<h1>DEVENEZ ADHÉRENT</h1><br /><br />

<p>Pour participer aux ateliers de l'association, il est obligatoire d'y adhérer. <br />
La cotisation annuelle fixée par l'assemblée générale est de 5 euros pour une personne physique et 15 euros pour une personne morale. <br />
L'adhésion couvre les frais de responsabilité civile, les documents mis à disposition pour les ateliers, le matériel et la possibilité d'accédern à la zone adhérent sur le site de l'association. <br />

Devenir adhérent est aussi un moyen de soutenir le projet de l'association.</p>
<p class="soul">Bulletion d'adhésion en pdf</p>


<h2>Tarifs</h2>

<p>Pour les ateliers tout public, nous pratiquons des tarifs en fonction du revenu. </p>
<p class="ita">Chaque cas étant particulier, n'hésitez pas à nous contacter.<br /></p> 

<table summary="Tarifs des ateliers au trimestre et à l'année" >
	<caption>Tarifs des ateliers en fonction de la période souhaitée</caption>
	<thead>
		<tr >
			<td></td>
			<th id="ateh">Ateliers hebdomadaires <br /> (2h)</th>
			<th id="ateb">Atelier en ligne bimensuel</th>
		</tr>
	</thead>
	<tbody>
		<tr >
			<td id="tri">Au trimestre</td>
			<td headers="ateh tri" >
				Adhésion <br />
				+ <br />
				70/80/90 €
			</td>
			<td headers="ateb tri">
				Adhésion <br />
				+<br />
				50/60/70 €
			</td>
		</tr>
		<tr >
			<td id="ane">A l'année</td>
			<td headers="ateh ane">
				Adhésion <br />
				+ <br />
				210 €
			</td>
			<td headers="ateb ane">
				Adhésion <br />
				+<br />
				150 €
			</td>
		</tr>
	</tbody>
</table> 
<br /><br />
<table summary="Autres ateliers disponibles" >
<caption>Tarifs des autres ateliers mis à disposition</caption>
	<thead>
		<tr >
			<td id="we">Ateliers <br />week-end <br />(3-4h)</td>
			<td id="dem">Ateliers à la <br />demande</td>
			<td id="spec">Ateliers <br />spécifiques</td>
			<td id="enf">Ateliers <br />enfants-ados</td>
		</tr>
	</thead>
	<tbody>
		<tr >
			<td headers="we">Entre <br />15 et 25 €</td>
			<td colspan="3" headers="dem spec enf">Sur devis <br /> (tarif horaire pratiqué en général : 50 €)</td>
		</tr>
	</tbody>
</table>

<?php

	$f->displayFooter();
?>