function siretInfo(val){
    val = val.split(" ").join("");
    $("#demande_siret").val(val);
    $.get("https://entreprise.data.gouv.fr/api/sirene/v3/etablissements/"+val, function (reponse) {
        //	$(demande_ville_chosen).hide();
        //	$(demande_ville).show();
        //$(demande_adresse_chosen).hide();
        //	$(demande_adresse).show();

        //	$(demande_ville_chosen).load(location.href + " #demande_ville");
        //$(demande_ville).prop('disabled', true);
        //$(demande_adresse).prop('disabled', true);


        console.log(reponse);

        $("#demande_adresse option").remove();
        $("#demande_ville option").remove();
        $("#demande_raisonsocial_nom").val(reponse.etablissement.unite_legale.denomination);
        $("#demande_code_postal").val(reponse.etablissement.code_postal);
        $("#demande_ville").append(new Option(reponse.etablissement.libelle_commune, reponse.etablissement.libelle_commune));
        $("#demande_ville").trigger('chosen:updated');
        var adresse = '';
        console.log(reponse.etablissement.numero_voie);
        if(reponse.etablissement.numero_voie != null){adresse += reponse.etablissement.numero_voie + ' '}
        if(reponse.etablissement.type_voie != null){adresse += reponse.etablissement.type_voie + ' '}
        if(reponse.etablissement.libelle_voie != null){adresse += reponse.etablissement.libelle_voie}
        if(reponse.etablissement.complement_adresse != null){adresse += " | Complément : " + reponse.etablissement.complement_adresse}
        console.log(adresse);
        $("#demande_adresse").append(new Option(adresse,adresse));
        //	$("#demande_adresse").append(new Option(reponse.etablissement.numero_voie +' '+reponse.etablissement.type_voie +' '+ reponse.etablissement.libelle_voie, reponse.etablissement.numero_voie +' '+reponse.etablissement.type_voie +' '+ reponse.etablissement.libelle_voie));
        $("#demande_adresse").trigger('chosen:updated');		//$(demande_adresse).append(new Option(reponse.etablissement.numero_voie +' '+reponse.etablissement.type_voie +' '+ reponse.etablissement.libelle_voie , reponse.etablissement.numero_voie +' '+reponse.etablissement.type_voie +' '+ reponse.etablissement.libelle_voie));
        $("#demande_cedex").val(reponse.etablissement.code_cedex);
        $("#demande_code_naf").val(reponse.etablissement.activite_principale);




    });

}