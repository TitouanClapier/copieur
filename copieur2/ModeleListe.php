<html lang="fr">

<head>
  <?php
  session_start();
  include_once 'head.php';
  include_once 'Connexion_PDO_COPIEUR.php';
  include_once 'ModeleCreate.php';
  include_once 'ModeleDelete.php';
  $db = Connexion_PDO_COPIEUR::getInstance();
  $db = $db->getDbh();
  include_once 'Connexion_PDO_ORGANIGRAMME.php';
  $dborg = Connexion_PDO_ORGANIGRAMME::getInstance();
  $dborg = $dborg->getDbh();
  $color = 'red';
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion Modeles</title>
</head>

<body>
  <!--Navigation bar-->
  <?php include_once 'nav.php'; ?>
  <div style="padding: 3px;">
    <h1>Gestion Modeles</h1>
    <!--CREATE-->
    <form method="post" name="form1">
      <label id="label1" class="invisible" for="libelle">libelle</label>
      <input id="input1" class="invisible form-control" type="text" name="libelle" id="libelle">
      <label id="label2" class="invisible" for="lien">lien</label>
      <input id="input2" class="invisible form-control" type="text" name="lien" id="lien">

      <span id="btn3" class="invisible">
        <button class="btn btn-outline-success" onClick="change('btn3','btn4'),invisible('label1'),invisible('label2'),invisible('input1'),invisible('input2')" type="submit"><i class="bi bi-check2"></i></button>
      </span>
      <span id="btn4" class="visible">

        <a href="#" class="btn btn-outline-success" onClick="invisible('btn4'),visible('btn3'),visible('label1'),visible('label2'),visible('input1'),visible('input2')"><i class="bi bi-plus-lg"></i></a>
      </span>
    </form>

    <?php $compte = 0;
    $sql = "SELECT libelle, lien, modele.id as id , count(modele_id) as nombre, modele_id FROM modele
      left JOIN copieur on modele_id = modele.id
      group by id;";
    $result = $con->query($sql); ?>
    <!--READ-->
    <table class="table-bordered " table-dark id='table'>
      <thead class="thead-dark">
        <tr>
          <th>Modele</th>
          <th>Lien</th>
          <th>nombre de copieurs</th>
        </tr>
      </thead>
      <tbody>
        <form name="form2" method="POST" action="ModeleUpdate.php">
          <?php foreach ($result as $row) : {
              $compte = $compte + 1; ?>
              <tr>
                <?php $id = $row['id'] ?>
                <td>
                  <span id="ligne1<?php echo $row['id'] ?>" class="visible">
                    <?php echo $row['libelle']; ?>
                  </span>
                  <span id="ligne2<?php echo $row['id'] ?>" class="invisible">
                    <input class="form-control" name="libelle" type="text" value="<?php echo $row['libelle']; ?>">
                  </span>
                </td>
                <td>
                  <span id="ligne3<?php echo $row['id'] ?>" class="visible">
                    <?php echo $row['lien']; ?>
                  </span>
                  <span id="ligne4<?php echo $row['id'] ?>" class="invisible">
                    <input class="form-control" name="lien" type="text" value="<?php echo $row['lien']; ?>">
                  </span>
                </td>
                <td><?php echo $row['nombre']; ?></td>

                <td>
                  <a href="#ligne1<?php echo $row['id'] ?>" class="visible" id="btn1<?php echo $row['id'] ?>" onClick="change('ligne1<?php echo $row['id'] ?>','ligne2<?php echo $row['id'] ?>'); change('ligne3<?php echo $row['id'] ?>','ligne4<?php echo $row['id'] ?>'); change('btn1<?php echo $row['id'] ?>','btn2<?php echo $row['id'] ?>'); invisible('supr<?php echo $row['id'] ?>');">
                    <h6><i class="bi bi-pencil"></h6></i>
                  </a>
                  <span id="btn2<?php echo $row['id'] ?>" class="invisible">
                    <button class="btn btn-outline-success" id="btn2" name="btn2<?php echo $row['id'] ?>" type="submit" value="enregistrer"><i class="bi bi-check2"></i></button>
                  </span>
                  <span id="supr<?php echo $row['id'] ?>" class="visible">
                    <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce modele ?');" href="ModeleDelete.php?id=<?= $row['id'] ?>" style="color: red">
                      <h5><i class="bi bi-trash3-fill"></h5></i>
                    </a>
                    <h6>
                  </span>
                </td>
              </tr>
          <?php }
          endforeach; ?>
        </form>
        Liste de tous les modeles:(<?php echo ($compte) ?> )
      </tbody>
    </table>
  </div>
</body>

</html>