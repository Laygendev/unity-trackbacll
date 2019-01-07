<?php
require_once( './config.php' );

try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}


$hash = $_GET['hash'];

$realHash = md5($_GET['pseudo'] . $_GET['password'] . $secretKey); 
if($realHash == $hash) { 
  
    $sth = $dbh->prepare('SELECT id, number_point FROM account WHERE account=:account AND password=:password');
    $sth->execute( array( 'account' => $_GET['pseudo'], 'password' => $_GET['password'] ) );
    $data = $sth->fetch();
    
    if ( $data ) {
      echo json_encode( array(
        'success'      => true,
        'id'           => $data['id'],
        'pseudo'       => $_GET['pseudo'],
        'points' => $data['points'],
      ) );
    } else {
      echo json_encode( array(
        'success' => false,
        'message' => 'Identifiant invalide',
      ) );
    }
} 
?>