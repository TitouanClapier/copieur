<html lang="fr">
<head>  
    <?php session_start(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once 'head.php';?>
	<title>Connexion</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

		<?php include_once 'nav.php';
		// Pop-up si la connexion n'a pas réussi
		if(isset($_GET['error'])){
				echo '<script> errorConnexion(); </script>';
		}
		?>

		<div class="mt-5 pb-3 cadre">
			<h1 class="pt-4" style="text-align:center;font-weight:bold;padding-bottom:1em;">Connexion</h1>
			<form style="width : 80%;margin-left:auto;margin-right:auto;" method="POST" action="login.php" autocomplete="off">
				<!-- Entrée de l'identifiant -->
				<div class="form-group">
					<label class="col-sm-auto col-form-label color" style="padding-left:43%;padding-bottom:1em;">Identifiant</label>
					<div class="col-sm-10 m-auto">
						<input class="form-control" name="id" placeholder="Identifiant" />
					</div>
				</div>

				<!-- Entrée du mot de passe -->
				<div class="form-group mt-4">
					<label class="form-label color" style="padding-left:42%;padding-bottom:1em;padding-top:1em;">Mot de passe</label>
					<div class="col-sm-10 m-auto">
						<input type="password" class="form-control" name="pw" placeholder="Mot de passe"/>
					</div>
				</div>
				<input type="submit" value="Se connecter" class="btn btn-warning" style="display:block;margin-left:auto;margin-right:auto;width:10em;margin-top:3em;;font-weight:bold;"/>
			</form>
		</div>
	</body>
</html>