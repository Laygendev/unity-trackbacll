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

if ( empty( $_GET['pseudo'] ) ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Champ pseudo requis',
  ) );
  exit;
}

if ( empty( $_GET['password'] ) ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Champ mot de passe requis',
  ) );
  exit;
}

if ( strlen( $_GET['pseudo'] ) < 3 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Pseudo minimum 3 caractères',
  ) );
  exit;
}

if ( strlen( $_GET['password'] ) < 6 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Password minimum 6 caractères',
  ) );
  exit;
}

if ( strlen( $_GET['pseudo'] ) > 15 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Pseudo maximum 15 caractères',
  ) );
  exit;
}
if ( strlen( $_GET['password'] ) > 20 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Password maximum 20 caractères',
  ) );
  exit;
}

$realHash = md5($_GET['pseudo'] . $_GET['password'] . $secretKey); 
if($realHash == $hash) { 
  
    $sth = $dbh->prepare('SELECT id FROM account WHERE account=:account');
    $sth->execute( array( 'account' => $_GET['pseudo'] ) );
    $data = $sth->fetch();
  
    if ( $data == false ) {
      $sth = $dbh->prepare('INSERT INTO account VALUES (null, :account, :password)');
      try {
          $sth->execute( array( 'account' => $_GET['pseudo'], 'password' => $_GET['password'] ) );
          
          echo json_encode( array(
            'success' => true,
          ) );
      } catch(Exception $e) {
          echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
      }
    } else {
      echo json_encode( array(
        'success' => false,
        'message' => 'Pseudo déjà utilisé',
      ) );
    }
} 
?>