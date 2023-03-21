<!-- CSS -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" href="css/jquery.tablesorter.pager.css">
<link href="css/theme.default.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/main.css">

<!-- JS -->

<script src="js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> 
<script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-tablesorter-min.js"></script>
<script src="js/jquery.tablesorter.pager.js"></script>
<script src="js/jquery.tablesorter.widgets.js"></script>
<script src="js/widget-math.js"></script>



<script>
  // Affiche une erreur quand la personne ne rentre pas de critères de recherche
  function errorRecherche() {
    alert("Vous n'avez pas sélectionné de critère de recherche");
  }
  // Affiche une erreur quand un fichier joint est trop lourd
  function errorAjout() {
    alert("Ajout de l'élément refusé, un fichier est trop lourd !")
  }

  function errorModif() {
    alert("Modification de l'élément refusé, un fichier est trop lourd !")
  }
  // Affiche une erreur quand les identifiants pour ce connecter ne sont pas corrects
  function errorConnexion() {
    alert("Connexion refusée, vérifiez vos identifiants !")
  }
  // Script pour tablesorter
  $(function() {

    var $table = $('#table').tablesorter({
      theme: 'default',
      dateFormat: "ddmmyyyy",
      widgets: ["filter"], // zebra
      widgetOptions: {
        filter_external: '.search',
        // add a default type search to the first name column
        filter_defaultFilter: {
          1: '~{query}'
        },
        // include column filters
        filter_columnFilters: true,
        filter_placeholder: {
          search: 'Rechercher...'
        },
        filter_saveFilters: false, // true de base
        filter_reset: '.reset'
      }
    });
  });


  // Script pour le bouton permettant de revenir en haut de la page
  $(function() {
    $(function() {
      $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
          $('.top').css('right', '10px');
        } else {
          $('.top').removeAttr('style');
        }
      });
    });
  });

  $(function() {
    $("table").tablesorter({
      headers: {
        6: {
          sorter: 'customDate'
        }
      }
    });
  });


  function change(ma_div1, ma_div2) {
    // La div placÃ©e en prmeiÃ¨re position "ma_div1" est la div qui est visible et qui va passer en invisible.
    // La div placÃ©e en seconde position "ma_div2" est la div qui est invisible et qui va passer en visible.
    document.getElementById(ma_div1).className = "invisible";
    document.getElementById(ma_div2).className = "visible";
  }

  function visible(ma_div1) {
    // La div placÃ©e en prmeiÃ¨re position "ma_div1" est la div qui est visible et qui va passer en visible.
    document.getElementById(ma_div1).className = "visible";
  }

  function invisible(ma_div1) {
    // La div placÃ©e en prmeiÃ¨re position "ma_div1" est la div qui est visible et qui va passer en invisible.
    document.getElementById(ma_div1).className = "invisible";
  }
</script>