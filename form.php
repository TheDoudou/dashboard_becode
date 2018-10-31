<!doctype html>
<html lang="fr">
  <head>
    <title>Envois question</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- Bootstrap CSS -->
  </head>
  <body>
    <? 
    $db_host = '';
    $db_login = '';
    $db_pass = '';
    $db_name = '';
    if ($_POST['text'] != '') {
    
      $mysqli = mysqli_connect($db_host, $db_login, $db_pass, $db_name);
      if ($mysqli->connect_errno) {
          echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
      }
      $text = $mysqli->real_escape_string($_POST['text']);
      $req = "INSERT INTO dashboard (id, question, date) VALUES (NULL, '".$text."', '0000-00-00')";
      $mysqli->query($req);
      echo '<p>Ajout dans la DB ok.</p>';
      if ($_GET['no'] != 'redirect')
        header('Location: index.php'); 
    }
    ?>
    
    <form method="post">
      <p class="question">Question que vous avez eu à un entretien ?</p><div></div>
      <input type="text" name="text" size=125>
      <input type="submit" value="Submit">
    </form>
  </body>
</html>