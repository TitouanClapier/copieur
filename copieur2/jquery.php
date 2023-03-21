<!-- script jquery -->
<script src="js/jquery.js"></script>
<script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-tablesorter-min.js"></script>
<script src="js/jquery.tablesorter.pager.js"></script>
<script src="js/jquery.tablesorter.widgets.js"></script>
<link href="bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">

<!-- pager plugin -->
<link rel="stylesheet" href="css/jquery.tablesorter.pager.css">
<script src="js/jquery.tablesorter.pager.js"></script>

<script>
  // Affiche une erreur quand la personne ne rentre pas de critères de recherche
function errorRecherche(){
  alert("Vous n'avez pas sélectionné de critère de recherche");
}
  // Affiche une erreur quand un fichier joint est trop lourd
  function errorAjout(){
    alert("Ajout de l'élément refusé, un fichier est trop lourd !")
}
  function errorModif(){
    alert("Modification de l'élément refusé, un fichier est trop lourd !")
}
  // Affiche une erreur quand les identifiants pour ce connecter ne sont pas corrects
function errorConnexion(){
  alert("Connexion refusée, vérifiez vos identifiants !")
}
  // Script pour tablesorter
  $(function() {

      var $table = $('#table').tablesorter({
      theme: 'default',
      widgets: ["filter"], // zebra
      widgetOptions : {
          filter_external : '.search',
          // add a default type search to the first name column
          filter_defaultFilter: { 1 : '~{query}' },
          // include column filters
          filter_columnFilters: true,
          filter_placeholder: { search : 'Rechercher...' },
          filter_saveFilters : false, // true de base
          filter_reset: '.reset'
          }
      });
  });

// Script pour le bouton permettant de revenir en haut de la page
$(function(){
  $(function () {
      $(window).scroll(function () {
          if ($(this).scrollTop() > 200 ) { 
              $('.top').css('right','10px');
          } else { 
              $('.top').removeAttr( 'style' );
          }
      });
  });
});
</script>


<style>
.tablesorter-pager .btn-group-sm .btn {
  font-size: 1.2em; /* make pager arrows more visible */
}