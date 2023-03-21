function numeroTiers(numero){

    if(window.XMLHttpRequest)
        xhr = new XMLHttpRequest();
    else if(window.ActiveXObject){ // Internet Explorer
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }}

    xhr.open('POST', '../public/index.php?route=coriolis', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("numero="+numero);


    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {

            var response = JSON.parse(xhr.response);
            var date =  response[0][0].TIERDATNAI;
            console.log(response[0][0]);



            if(response[0][0].TIERCODCAT == "01PARTICUL"){
                $("#demande_type").val(1);
                typeChoix();
                if(response[0][0].TIERTITRE == "M."){$("#demande_civilite").val(1);}
                else if(response[0][0].TIERTITRE == "MME"){$("#demande_civilite").val(2);}
                $("#demande_profession").val(response[0][0].TIERINF1);
                $("#demande_date_naissance").val(date.substr(0, 4)+ '-' + date.substr(4,2) + '-' + date.substr(6, 2));
                if (response[0][0].TIERVILNAI != null){
                    $("#demande_lieu_naissance").append(new Option(response[0][0].TIERVILNAI, response[0][0].TIERVILNAI)).trigger('chosen:updated');
                }
                //  $(demande_departement_naissance).val();
            }


            else if(response[0][0].TIERCODCAT == "01PROFLIB")
            {
                $("#demande_type").val(3);
                typeChoix();

                if(response[0][0].TIERTITRE == "M."){$("#demande_civilite").val(1);}
                else if(response[0][0].TIERTITRE == "MME"){$("#demande_civilite").val(2);}
                $("#demande_profession").val(response[0][0].T1IERINF1);
                $("#demande_siret").val(response[0][0].TIERSIRET);
                $("#demande_cedex").val(response[0][0].TIERCEDEX);
                $("#demande_date_naissance").val(date.substr(0, 4)+ '-' + date.substr(4,2) + '-' + date.substr(6, 2));
                $("#demande_lieu_naissance").append(new Option(response[0][0].TIERVILNAI, response[0][0].TIERVILNAI)).trigger('chosen:updated');
                //  $(demande_departement_naissance).val();


            }
            else if(response[0][0].TIERCODCAT == "50ASSO"){
                $("#demande_type").val(2);
                typeChoix();
                $("#demande_association").click();
                $("#demande_siret").val(response[0][0].TIERSIRET);
                $("#demande_code_naf").val(response[0][0].TIERCODNAF);
                $("#demande_numero_prefecture_waldeck").val(response[0][0].TIERWALDECK);
                //	 $(demande_date_declaration).val(response[0][0].)
                $("#demande_cedex").val(response[0][0].TIERCEDEX);



            }

            else if(response[0][0].TIERCODCAT == "50SOCIETE" || "60CAMGEN"){
                $("#demande_type").val(2);
                typeChoix();
                $("#demande_siret").val(response[0][0].TIERSIRET);
                $("#demande_code_naf").val(response[0][0].TIERCODNAF);
                $("#demande_cedex").val(response[0][0].TIERCEDEX);


            }


            $("#demande_pays").val(response[1]);
            $("#demande_raisonsocial_nom").val(response[0][0].TIERRS1);
            $("#demande_raisonsocial2_prenom").val(response[0][0].TIERRS2);
            $("#demande_telephone").val(response[0][0].TIERTEL);
            $("#demande_courriel").val(response[0][0].TIEREMAIL);
            $("#demande_code_postal").val(response[0][0].TIERCODPOS);
            $("#demande_ville").append(new Option(response[0][0].TIERVILLE, response[0][0].TIERVILLE)).trigger('chosen:updated');
            var adresse = response[0][0].TIERAD1;
            if(response[0][0].TIERAD2 != null){adresse += ' ' + response[0][0].TIERAD2}
            else if(response[0][0].TIERAD3 != null){adresse += ' ' + response[0][0].TIERAD3}
            $("#demande_adresse").append(new Option(adresse, adresse)).trigger('chosen:updated');

        }
    };

}