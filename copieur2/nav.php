<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Copieurs CD28</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php"><img src="images/accueil.png" width="22" height="22" class="d-inline-block align-center" alt=""> Accueil</a>
        </li>
        <?php if (isset($_SESSION["cop_identifiant"]) and $_SESSION["cop_identifiant"] != Null) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Copieur
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="CopieurCreate.php"><img src="images/plus.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Ajouter un copieur</a></li>
              <li><a class="dropdown-item" href="Copieurliste.php"><img src="images/liste.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Liste des copieurs</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Gestion
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="SiteListe.php"><img src="images/localisateur.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Sites</a></li>
              <li><a class="dropdown-item" href="ServiceListe.php"><img src="images/public-service.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Services</a></li>
              <li><a class="dropdown-item" href="ModeleListe.php"><img src="images/imprimer.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Modèles</a></li>
              <li><a class="dropdown-item" href="TypeDocumentListe.php"><img src="images/feuille.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Types de documents</a></li>
              <li><a class="dropdown-item" href="BilanListe.php"><img src="images/graphique.png" width="22" height="22" class="d-inline-block align-center" alt=""> &nbsp; Bilan financier</a></li>
            </ul>
          </li>


        <?php } ?>
      </ul>
    </div>
    <?php
    if (isset($_SESSION["cop_identifiant"]) and $_SESSION["cop_identifiant"] != Null) {
    ?><span class="navbar-text">
        <a class="item"> <?php echo ($_SESSION["cop_prenom"]); ?></a>
        <a class="item"> <?php echo ($_SESSION["cop_nom"]); ?></a>
        <a class="item"> <?php echo ($_SESSION["cop_role"]); ?></a>
        <a class="navbar-brand" href="logout.php" style="color: red">Deconnexion</a><?php
                                                                                  } else {
                                                                                    ?><a class="navbar-brand" href="connexion.php">Connexion</a>

      <?php } ?>

      </span>
  </div>
</nav>