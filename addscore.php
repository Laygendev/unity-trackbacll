<?php
// Configuration
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'trackball';

$secretKey = "mySecretKey"; // Change this value to match the value stored in the client javascript below 

try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}

$hash = $_GET['hash'];

$realHash = md5($_GET['name'] . $_GET['time'] . $secretKey); 
if($realHash == $hash) { 
    $sth = $dbh->prepare('INSERT INTO scores VALUES (null, :id_level, :name, :time)');
    try {
        $sth->execute( array( 'id_level' => $_GET['id_level'], 'name' => $_GET['name'], 'time' => $_GET['time'] ) );
    } catch(Exception $e) {
        echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }
} 
?>