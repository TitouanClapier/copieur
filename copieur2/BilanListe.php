<!DOCTYPE html>
<html lang="fr">

<head>
  <?php
  session_start();

  include_once 'head.php';
  require_once '../annuaire/api.php';
  include_once 'Connexion_PDO_copieur.php';
  $db = Connexion_PDO_copieur::getInstance();
  $db = $db->getDbh();

  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bilan</title>
</head>

<body>
  <!--Navigation bar-->
  <?php include_once 'nav.php'; ?>
  <div style="padding: 5px;">

    <h1>Bilan <?php if (isset($_GET["annee"])) echo $_GET["annee"] ?> </h1>

    Choisir une année :
    <?php $annee = date("Y"); ?>
    <?php $debut = 2009; ?>

    <form>
      <select name="annee" id="dropdown">
        <option value=''>
          choisir...
        </option>
        <?php while ($debut - 1 < $annee) { ?>
          <option>
            <?php echo $annee;
            $annee = $annee - 1; ?>
          </option>
        <?php } ?>
      </select>
      <input type="submit" value="Confirmer">
    </form>

    <?php if (isset($_GET["annee"])) $annee = $_GET["annee"] ?>

  </div>

  <?php
  $sql = "SELECT id, libelle FROM type_document ";

  $query = $db->prepare($sql);
  $query->execute();
  $lesdocuments = $query->fetchAll(PDO::FETCH_ASSOC);

  ?>

  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <?php
      foreach ($lesdocuments as $undocument) :
        $typedoc1 =  str_replace(' ', '_', $undocument['libelle']);

        $idtypedoc1 = $undocument['id'];
        $nom = $undocument['libelle'];

        if ($idtypedoc1 == 1) { ?>
          <button class="nav-link active" id="nav-<?php echo $typedoc1 ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $typedoc1 ?>" type="button" role="tab" aria-controls="nav-<?php echo $typedoc1 ?>" aria-selected="true"><?php echo $nom ?></button>
        <?php } else { ?>
          <button class="nav-link " id="nav-<?php echo $typedoc1 ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo $typedoc1 ?>" type="button" role="tab" aria-controls="nav-<?php echo $typedoc1 ?>" aria-selected="false"><?php echo $nom ?></button>

        <?php } ?>
      <?php endforeach; ?>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <?php
    foreach ($lesdocuments as $undocument) :
      $nom = $undocument['libelle'];
      $typedoc =  str_replace(' ', '_', $undocument['libelle']);
      $idtypedoc = $undocument['id'];
      $idtable = $undocument['id'] + 10;
    ?>

      <?php
      $compte = 0;
      $sql = "SELECT document.id, copieur.matricule, copieur.modele_id, modele.libelle, numero, date_reception, numero_mandat, numero_engagement, montant_ttc, type_doc_id  FROM document 
        left JOIN copieur on copieur.id  = copieur_id
        left JOIN modele on modele.id  = modele_id
        left JOIN type_document on type_document.id  = type_doc_id
        Where type_doc_id = $idtypedoc
        and Year(date_reception) = $annee ";

      $query = $db->prepare($sql);
      $query->execute();
      $listedocuments = $query->fetchAll(PDO::FETCH_ASSOC);




      if ($idtypedoc == 1) { ?>
        <div class="tab-pane fade show active" id="nav-<?php echo $typedoc ?>" role="tabpanel" aria-labelledby="nav-<?php echo $typedoc ?>-tab">
        <?php } else { ?>
          <div class="tab-pane fade" id="nav-<?php echo $typedoc ?>" role="tabpanel" aria-labelledby="nav-<?php echo $typedoc ?>-tab">
          <?php } ?>

          <table class="table-bordered " table-dark id='table"<?php echo $idtable ?>"'>
            <thead class="thead-dark"> <!-- add class="thead-light" for a light header -->
              <tr>
                <th>Matricule</th>
                <th>Modèle</th>
                <th>N° document</th>
                <th>Date</th>
                <th>N° de mandat</th>
                <th>N° d'engagement</th>
                <th>Montant en euros</th>

              </tr>
            </thead>
            <tbody>
              <?php $compte = 0; ?>

              <?php foreach ($listedocuments as $document) : $compte = $compte + 1; ?>
                <tr>

                  <td> <u> <a href="documentDetail.php?id=<?= $document['id'] ?>"><?php echo $document['matricule']; ?></a> <u> </td>
                  <td><?php echo $document['libelle']; ?></td>
                  <td><?php echo $document['numero']; ?></td>
                  <td> <?php $ladate = $document['date_reception'];
                        echo date('d/m/Y', strtotime($ladate)); ?> </td>
                  <td><?php echo $document['numero_mandat']; ?></td>
                  <td><?php echo $document['numero_engagement']; ?></td>
                  <td><?php echo $document['montant_ttc']; ?></td>
                <tr>
                  <td>Count (row-count)</td>
                  <td data-math="row-count"></td>
                </tr>
                </tr>
              <?php endforeach; ?>
              Liste de tous les <?php echo $nom ?> : (<?php echo ($compte) ?>)
            </tbody>
          </table>
          Sum of Money: <span class="total"></span>
          <script>
            $('table')
              .tablesorter({
                widgets: ['filter', 'math'],
                widgetOptions: {
                  math_data: 'math', // data-math attribute
                  math_ignore: [0, 1],
                  math_none: 'N/A', // no matching math elements found (text added to cell)
                  math_complete: function($cell, wo, result, value, arry) {
                    var txt = '<span class="align-decimal">' +
                      (value === wo.math_none ? '' : '$ ') +
                      result + '</span>';
                    if ($cell.attr('data-math') === 'all-sum') {
                      // when the "all-sum" is processed, add a count to the end
                      return txt + ' (Sum of ' + arry.length + ' cells)';
                    }
                    return txt;
                  },
                  math_completed: function(c) {
                    // c = table.config
                    // called after all math calculations have completed
                    console.log('math calculations complete', c.$table.find('[data-math="all-sum"]:first').text());
                  },
                  // cell data-attribute containing the math value to use (added v2.31.1)
                  math_textAttr: '',
                  // see "Mask Examples" section
                  math_mask: '#,##0.00',
                  math_prefix: '', // custom string added before the math_mask value (usually HTML)
                  math_suffix: '', // custom string added after the math_mask value
                  // event triggered on the table which makes the math widget update all data-math cells (default shown)
                  math_event: 'recalculate',
                  // math calculation priorities (default shown)... rows are first, then column above/below,
                  // then entire column, and lastly "all" which is not included because it should always be last
                  math_priority: ['row', 'above', 'below', 'col'],
                  // set row filter to limit which table rows are included in the calculation (v2.25.0)
                  // e.g. math_rowFilter : ':visible:not(.filtered)' (default behavior when math_rowFilter isn't set)
                  // or math_rowFilter : ':visible'; default is an empty string
                  math_rowFilter: ''
                }
              });
          </script>
          </div>
        <?php endforeach; ?>
        </div>
  </div>
</body>

</html>