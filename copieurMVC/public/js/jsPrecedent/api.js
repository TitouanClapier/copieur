
ajaxGet("https://entreprise.data.gouv.fr/api/sirene/v3/etablissements/"+siret, function (reponse) {
	var info = JSON.parse(reponse);
    descriptionElt.textContent = premierMinistre.description;
    var logoElt = document.createElement("img");
    logoElt.src = premierMinistre.logo;
    premMinElt.appendChild(descriptionElt);
    premMinElt.appendChild(logoElt);
});
