$(function() {
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});

document.addEventListener("keydown", function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
}, true); // "true" => phase de capture

$(document).ready(function(){



    $(".info").css('opacity', '0.0'); // car les chosen select s'affiche mal avec un hide
    $("input[type='radio']").click(function(){

        if($('#demande_creation_modif_mode_0').is(':checked')){
            $(".crea").show();
            $(".modif").hide();

        }
        else if($('#demande_creation_modif_mode_1').is(':checked')){
            $(".crea").show();
            $(".modif").show();

        }
    });





    $(".crea").hide();
    $(".modif").hide();
    $(".physique").hide();
    $(".morale").hide();
    $(".liberale").hide();
    $(".association").hide();


    if($('#demande_creation_modif_mode_0').is(':checked')){
        $(".crea").show();
        $(".modif").hide();
    }
    else if($('#demande_creation_modif_mode_1').is(':checked')){
        $(".crea").show();
        $(".modif").show();
    }

    if($("#demande_association").is(':checked')){


        $(".association").show();

    }
    else {

        $(".association").hide();

    }

    if($("#demande_type option[value='1'] ").is(':selected')){
        console.log("1");

        $(".morale").hide();
        $(".liberale").hide();
        $(".association").hide();
        $(".physique").show();
        $(".info").css('opacity', '1');



        $("#demande_adresse").prop('disabled', false);
        $("#demande_ville").prop('disabled', false);


    }
    else if($("#demande_type option[value='2'] ").is(':selected')){
    console.log("2");
        $(".physique").hide();
        $(".liberale").hide();
        $(".morale").show();
        $(".info").css('opacity', '1');


        $("#demande_adresse").prop('disabled', false);
        $("#demande_ville").prop('disabled', false);

    }
    else if($("#demande_type option[value='3'] ").is(':selected')){
        console.log("3");
        $(".association").hide();
        $(".morale").hide();
        $(".physique").hide();
        $(".liberale").show();
        $(".info").css('opacity', '1');


        $("#demande_adresse").prop('disabled', false);
        $("#demande_ville").prop('disabled', false);
    }

    /* $.get("https://geo.api.gouv.fr/communes?codePostal="+$(demande_ville), function (reponse) {

         for (var i in reponse) {
             $(demande_ville).append(new Option(reponse[i].nom, reponse[i].nom));

         }

         $("#demande_ville").trigger('chosen:updated');


         });		 */
    $(function() {
        $("table").tablesorter({
            theme : "bootstrap",
            widthFixed: true,
            // widget code contained in the jquery.tablesorter.widgets.js file
            // use the zebra stripe widget if you plan on hiding any rows (filter widget)
            // the uitheme widget is NOT REQUIRED!
            widgets : [ "filter" ],

            widgetOptions : {
                // using the default zebra striping class name, so it actually isn't included in the theme variable above
                // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
                zebra : ["even", "odd"],

                // class names added to columns when sorted
                columns: [ "primary", "secondary", "tertiary" ],

                // reset filters button
                filter_reset : ".reset",

                // extra css class name (string or array) added to the filter element (input or select)
                filter_cssFilter: [
                    'form-control',
                    'form-control',
                    'form-control custom-select', // select needs custom class names :(
                    'form-control',
                    'form-control',
                    'form-control',
                    'form-control'
                ]

            }
        })
            .tablesorterPager({

                // target the pager markup - see the HTML block below
                container: $(".ts-pager"),

                // target the pager page select dropdown - choose a page
                cssGoto  : ".pagenum",

                // remove rows from the table to speed up the sort of large tables.
                // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                removeRows: false,

                // output string - default is '{page}/{totalPages}';
                // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
                output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

            });
    });
});

function associationClick()
{
    if($("#demande_association").is(':checked')){


        $(".association").show();

    }
    else {

        $(".association").hide();

    }
}

var i =0;
var count = 1; // Create a count
function ajoutPj()
{



    var html = $(".pj").first().clone();
    html.find('.guest_name').attr({ id: "pj_chemin_1"});
    html.find('.guest_name').attr({ name: "pj[chemin][1]"});//use the count to update this clone field
    // you can update all the attributes here before the clone is added
    $(".pj").last().after(html);//add the clone

    count++; // increase the count
}

function typeChoix()
{
    console.log('test');
    if($("#demande_type option[value='1'] ").is(':selected')){

        $(".info").css('opacity', '1');
        $(".morale").hide();
        $(".liberale").hide();
        $(".association").hide();
        $(".physique").show();

        document.getElementById("demande_association").checked = false;
        $("#demande_adresse").prop('disabled', false);
        $("#demande_adresse option").remove();
        $("demande_ville").prop('disabled', false);
        $("#demande_ville option").remove();
        $("#demande_lieu_naissance option").remove();
        $("#demande_code_postal").val("");
        $("#demande_cedex").val("");
    }
    else if($("#demande_type option[value='2'] ").is(':selected')){
        $(".info").css('opacity', '1');
        $(".physique").hide();
        $(".liberale").hide();
        $(".morale").show();


        $("#demande_adresse").prop('disabled', false);
        $("#demande_adresse option").remove();
        $("#demande_ville").prop('disabled', false);
        $("#demande_ville option").remove();
        $("#demande_lieu_naissance option").remove();
        $("#demande_code_postal").val("");
        $("#demande_cedex").val("");
    }
    else if($("#demande_type option[value='3'] ").is(':selected')){
        $(".info").css('opacity', '1');
        $(".morale").hide();
        $(".physique").hide();
        $(".liberale").show();
        $(".association").hide();

        document.getElementById("demande_association").checked = false;
        $("#demande_adresse").prop('disabled', false);
        $("#demande_adresse option").remove();
        $("#demande_ville").prop('disabled', false);
        $("#demande_ville option").remove();
        $("#demande_lieu_naissance option").remove();
        $("#demande_code_postal").val("");
        $("#demande_cedex").val("");
    }
}

function noFrance(lePays)
{

    if (lePays != "France" && lePays != "france" && lePays != 'FRANCE')
    {
        console.log(lePays)

        document.getElementById("demande_lieu_naissance2").hidden = false;
        document.getElementById("demande_lieu_naissance_chosen").hidden = true;
        $("#demande_lieu_naissance").prop("disabled", true);
        $("#demande_lieu_naissance2").prop("disabled", false);

        document.getElementById("demande_ville2").hidden = false;
        document.getElementById("demande_ville_chosen").hidden = true;
        $("#demande_ville").prop("disabled", true);
        $("#demande_ville2").prop("disabled", false);

        document.getElementById("demande_adresse2").hidden = false;
        document.getElementById("demande_adresse_chosen").hidden = true;
        $("#demande_adresse").prop("disabled", true);
        $("#demande_adresse2").prop("disabled", false);




    }
    else
    {
        document.getElementById("demande_lieu_naissance2").hidden = true;
        document.getElementById("demande_lieu_naissance_chosen").hidden = false;
        $("#demande_lieu_naissance").prop("disabled", false);
        $("#demande_lieu_naissance2").prop("disabled", true);

        document.getElementById("demande_ville2").hidden = true;
        document.getElementById("demande_ville_chosen").hidden = false;
        $("#demande_ville").prop("disabled", false);
        $("#demande_ville2").prop("disabled", true);

        document.getElementById("demande_adresse2").hidden = true;
        document.getElementById("demande_adresse_chosen").hidden = false;
        $("#demande_adresse").prop("disabled", false);
        $("#demande_adresse2").prop("disabled", true);
    }
}




