<?php
$db_host = '';
$db_login = '';
$db_pass = '';
$db_name = '';
$api_access = ''; // pseudo:token https://github.com/settings/tokens/new


$mysqli = mysqli_connect($db_host, $db_login, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$result = $mysqli->query("SELECT * FROM dashboard WHERE date = '".date('Y-m-d')."'"); // Checherche une citation avec la date du jour
if (mysqli_num_rows($result) == 0) // Si il n'y a pas d'entrée avec la date actuel il prend toutes les entrées sans date
    $result = $mysqli->query("SELECT * FROM dashboard WHERE date = '0000-00-00'");

$i = 0;

while ($row = $result->fetch_array()){
        $citations[$i][0] = $row['question'];
        $citations[$i][1] = $row['date'];
        $citations[$i][2] = $row['id'];
        $i++;
}

$rand = rand(0, $i-1);

if ($citations[$rand][1] == '0000-00-00') // Si la citation choisi en random n'a pas de date alors il set la date du jour
    $mysqli->query("UPDATE dashboard SET date = '".date('Y-m-d')."' WHERE dashboard.id = ".$citations[$rand][2]);

function github_request($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_USERAGENT, 'Agent smith');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERPWD, $api_access);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode(trim($output), true);
    return $result;
}
$repos = github_request('https://api.github.com/repos/becodeorg/CRL-Turing-2.6');
//print_r($repos);
$stars = $repos['stargazers_count'];
$watcher = $repos['subscribers_count'];

$contrib = github_request('https://api.github.com/repos/becodeorg/CRL-Turing-2.6/stats/contributors');
$i = 0;
foreach ($contrib as &$value) {
    $commit_total = $commit_total + $value['total'];
}

$comm = github_request('https://api.github.com/repos/becodeorg/CRL-Turing-2.6');
//print_r($comm);
?>



<!doctype html>
<html lang="fr">
  <head>
    <meta http-equiv="refresh" content="86460">
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
  </head>
  <body>
    <div class="test">
        <div class="citation_group">
            <div class="">
                <p class="citation"><? echo $citations[$rand][0]; ?></p>
            </div>
        </div>
        <div class="api_group">
            <p class="api"><i class="fas fa-star"></i>&nbsp;&nbsp;<? echo $stars; ?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-users"></i>&nbsp;&nbsp;<? echo $watcher; ?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-clock"></i>&nbsp;&nbsp;<? echo $commit_total; ?></p>
            
        </div>
        <a href="form.php"><img src="assets/img/logobecode2.jpg" alt="banierebecode" class="logo"></a>


        
        
    </div><br><br>
  </body>
</html>
