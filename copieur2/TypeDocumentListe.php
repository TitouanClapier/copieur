<html lang="fr">

<head>
  <?php
  session_start();
  include_once 'head.php';
  include_once 'Connexion_PDO_COPIEUR.php';
  include_once 'TypeDocumentCreate.php';
  include_once 'TypeDocumentDelete.php';
  $db = Connexion_PDO_COPIEUR::getInstance();
  $db = $db->getDbh();

  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion Modeles</title>
</head>

<body>
  <!--Navigation bar-->
  <?php include_once 'nav.php'; ?>
  <div style="padding: 5px;">
    <h1>Gestion Type de documents</h1>
    <!--CREATE-->
    <form method="post" name="form1">
      <label id="label1" class="invisible" for="libelle">libelle</label>
      <input id="input1" class="invisible form-control" type="text" name="libelle" id="libelle">
      <label id="label2" class="invisible" for="num_ordre">num_ordre</label>
      <input id="input2" class="invisible form-control" type="text" name="num_ordre" id="num_ordre">
      <label id="label3" class="invisible" for="invest_fonct">invest_fonct</label>

      <select id="input3" class="invisible form-control" type="text" name="invest_fonct" id="invest_fonct">  
        <option value="">Choisir...</option>  
        <option value="1">Investissement</option>  
        <option value="0">Fonctionnement</option>  
        <option value="-1">Autre</option>  
      </select>  

      <span id="btn3" class="invisible">
        <button class="btn btn-outline-success" onClick="change('btn3','btn4'),invisible('label1'),invisible('label3'),invisible('label3'),invisible('label2'),invisible('input1'),invisible('input2')" type="submit"><i class="bi bi-check2"></i></button>
      </span>
      <span id="btn4" class="visible">

        <a href="#" class="btn btn-outline-success" onClick="invisible('btn4'),visible('btn3'),visible('label1'),visible('label2'),visible('input1'),visible('input2'),visible('input3'),visible('label3')"><i class="bi bi-plus-lg"></i></a>
      </span>
    </form>

    <?php $compte = 0;
    $sql = "SELECT libelle, num_ordre, id, invest_fonct FROM type_document order by num_ordre ";
    $result = $con->query($sql); ?>
    <!--READ-->
    <table class="table-bordered " table-dark id='table'>
      <thead class="thead-dark">
        <tr>
          <th>Modele</th>
          <th>num_ordre</th>
          <th>Depense</th>
        </tr>
      </thead>
      <tbody>
        <form name="form2" method="POST" action="TypeDocumentUpdate.php">
          <?php foreach ($result as $row) : { ?>
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
                    <?php echo $row['num_ordre']; ?>
                    </span>

                  <span id="ligne4<?php echo $row['id'] ?>" class="invisible">
                    <input class="form-control" name="num_ordre" type="text" value="<?php echo $row['num_ordre']; ?>">
                  </span>
                </td>
                <td>
                  <span id="ligne5<?php echo $row['id'] ?>" class="visible">
                  <?php if ($row['invest_fonct'] == -1) echo 'Autre' ; elseif ($row['invest_fonct'] == 0) echo 'Fonctionnement' ; elseif ($row['invest_fonct'] == 1) echo 'Investissement' ; ?>
                  </span>
                  <span id="ligne6<?php echo $row['id'] ?>" class="invisible">
                    <select name="invest_fonct" type="text" value="<?php echo $row['invest_fonct']; ?>">  
                      <option value="<?php echo $row['invest_fonct']; ?>"><?php if ($row['invest_fonct'] == -1) echo 'Autre' ; elseif ($row['invest_fonct'] == 0) echo 'Fonctionnement' ; elseif ($row['invest_fonct'] == 1) echo 'Investissement' ; ?>...</option>  
                      <option value="1">Investissement</option>  
                      <option value="0">Fonctionnement</option>  
                      <option value="-1">Autre</option>  
                    </select>  
                  </span>
                </td>

                <td>
                  <a href="#ligne1<?php echo $row['id'] ?>" class="visible" id="btn1<?php echo $row['id'] ?>" 
                  onClick="change('ligne1<?php echo $row['id'] ?>','ligne2<?php echo $row['id'] ?>'); change('ligne3<?php echo $row['id'] ?>','ligne4<?php echo $row['id'] ?>'); change('ligne5<?php echo $row['id'] ?>','ligne6<?php echo $row['id'] ?>'); change('btn1<?php echo $row['id'] ?>','btn2<?php echo $row['id'] ?>'); invisible('supr<?php echo $row['id'] ?>');">

                  <h6><i class="bi bi-pencil"></h6></i>
                  </a>
                  <span id="btn2<?php echo $row['id'] ?>" class="invisible">
                    <button class="btn btn-outline-success" id="btn2" name="btn2<?php echo $row['id'] ?>" type="submit" value="enregistrer"><i class="bi bi-check2"></i></button>
                  </span>
                  </td><td>
                  <span id="supr<?php echo $row['id'] ?>" class="visible">
                    <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de document ?');" href="TypeDocumentDelete.php?id=<?= $row['id'] ?>" style="color: red">
                      <h5><i class="bi bi-trash3-fill"></h5></i>
                    </a>
                  </span>
                </td>
              </tr>
          <?php }
          endforeach; ?>
        </form>
      </tbody>
    </table>
  </div>
</body>

</html>