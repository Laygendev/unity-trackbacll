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

$realHash = md5($_GET['pseudo'] . $_GET['password'] . $secretKey); 
if($realHash == $hash) { 
  
    $sth = $dbh->prepare('SELECT id FROM account WHERE account=:account AND password=:password');
    $sth->execute( array( 'account' => $_GET['pseudo'], 'password' => $_GET['password'] ) );
    $data = $sth->fetch();
    
    if ( $data ) {
      echo json_encode( array(
        'success' => true,
        'id'      => $data['id'],
        'pseudo'  => $_GET['pseudo'],
      ) );
    } else {
      echo json_encode( array(
        'success' => false,
        'message' => 'Identifiant invalide',
      ) );
    }
} 
?>