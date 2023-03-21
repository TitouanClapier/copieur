$(document).ready(function() {
    var taille = 0;
    $(".chosen-search-input:eq(0)").keypress(function() {

        var code = $("#demande_code_postal").val();


        $.get("https://geo.api.gouv.fr/communes?codePostal="+code, function (reponse) {

            if (taille != reponse.length)
            {
                $("#demande_ville option").remove();
                console.log("taille : "+ taille + "Reponse :" + reponse.length)
                taille = reponse.length;
                for (var i in reponse) {
                    $("#demande_ville").append(new Option(reponse[i].nom, reponse[i].nom));
                }
                $("#demande_ville").trigger('chosen:updated');
            }
        });

    });



    // .chosen-search-input:eq(1) == adresse
    $(".chosen-search-input:eq(1)").keypress(function(){

        var contenue;
        var postal = $("#demande_code_postal").val();
        var ville = $("#demande_ville").val();
        $.get('https://api-adresse.data.gouv.fr/search/?q='+ville+'&type=municipality&limit=1', function (reponse) { // TODO limite
            var codeVille = reponse.features[0].properties.citycode;
            console.log(reponse);
            $.get('https://api-adresse.data.gouv.fr/search/?q='+$(".chosen-search-input:eq(1)").val()+'&citycode='+codeVille+'&autocomplete=0', function (reponse) {

                if(reponse.features.length != 0){
                    $("#demande_adresse option").remove();
                    for(i in reponse.features){
                        $("#demande_adresse").append(new Option(reponse.features[i].properties.name, reponse.features[i].properties.name));
                    }

                    console.log(typeof reponse.features[0].properties.name);
                    var contenue = $(".chosen-search-input:eq(1)").val();
                    $("#demande_adresse").trigger('chosen:updated');
                    $(".chosen-search-input:eq(1)").val(contenue);
                }

            });
        });

    });

    $(".chosen-search-input:eq(2)").keyup(function() {
        var commune;
        if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 37 && event.keyCode != 39) {

            if ($("#demande_departement_naissance").val().length == 1) {
                $("#demande_departement_naissance").val(('0'.concat($("#demande_departement_naissance").val())));
            }

            var commune = $(".chosen-search-input:eq(2)").val();
            var codeDep = $("#demande_departement_naissance").val();
            $("#demande_lieu_naissance option").remove();


            $.get("https://geo.api.gouv.fr/communes?nom=" + commune + "&codeDepartement=" + codeDep + "&limit=5", function (reponse) {
                if (reponse.length >= 1) {
                    for (var i in reponse) {
                        $("#demande_lieu_naissance").append(new Option(reponse[i].nom, reponse[i].nom));
                    }
                    commune = $(".chosen-search-input:eq(2)").val();
                    $("#demande_lieu_naissance").trigger('chosen:updated');
                    $(".chosen-search-input:eq(2)").val(commune);
                }
            })
        }
    });
});




/*








  //  $(".info").hide();
    $(".chosen-search-input:eq(1)").keypress(function(){ //eq(1) pour le deuxieme element avec la classe chosen-search-input
//		if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 37 && event.keyCode != 39){

        var contenue;
        var postal = $("#demande_code_postal").val();
        var ville = $("#demande_ville").val();
        $.get('https://api-adresse.data.gouv.fr/search/?q='+ville+'&type=municipality&limit=1', function (reponse) {
            var codeVille = reponse.features[0].properties.citycode;
            $.get('https://api-adresse.data.gouv.fr/search/?q='+$(".chosen-search-input:eq(1)").val()+'&citycode='+codeVille+'&autocomplete=0', function (reponse) {
                console.log(reponse);
                if(reponse.features.length != 0){
                    $("#demande_adresse option").remove();
                    for(i in reponse.features){
                        $("#demande_adresse").append(new Option(reponse.features[i].properties.name, reponse.features[i].properties.name));
                    }

                    console.log(typeof reponse.features[0].properties.name);
                    var contenue = $(".chosen-search-input:eq(1)").val();
                    $("#demande_adresse").trigger('chosen:updated');
                    $(".chosen-search-input:eq(1)").val(contenue);
                }

            });
        });
        //	}
    });

*/

