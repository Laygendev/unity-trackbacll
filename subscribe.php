<?php
// Configuration
require_once( './config.php' );

try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}


$hash = $_GET['hash'];

if ( empty( $_GET['pseudo'] ) ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Account field required.',
  ) );
  exit;
}

if ( empty( $_GET['email'] ) ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Email field is required.',
  ) );
  exit;
}

if (! preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $_GET['email'])) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Invalid email.',
  ) );
  exit;
}

if ( empty( $_GET['password'] ) ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Password field required.',
  ) );
  exit;
}

if ( strlen( $_GET['pseudo'] ) < 3 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Account 3 characters minimum.',
  ) );
  exit;
}

if ( strlen( $_GET['password'] ) < 6 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Password 6 characters minimum.',
  ) );
  exit;
}

if ( strlen( $_GET['pseudo'] ) > 15 ) {
  echo json_encode( array(
    'success' => false,
    'message' => 'Account 15 characters maximum.',
  ) );
  exit;
}

$realHash = md5($_GET['pseudo'] . $_GET['email'] . $_GET['password'] . $secretKey); 
if($realHash == $hash) { 
  
    $sth = $dbh->prepare('SELECT id FROM account WHERE account=:account OR email=:email');
    $sth->execute( array( 'account' => $_GET['pseudo'], 'email' => $_GET['email'] ) );
    $data = $sth->fetch();
    
    if ( ! $data ) {
      $sth = $dbh->prepare('INSERT INTO account VALUES (null, :account, :email, :password, 0)');
      try {
          $sth->execute( array( 'account' => $_GET['pseudo'], 'email' => $_GET['email'], 'password' => $_GET['password'] ) );
          
          echo json_encode( array(
            'success' => true,
            'id'     => $dbh->lastInsertId(),
            'pseudo' => $_GET['pseudo'],
            'points' => 0,
          ) );
      } catch(Exception $e) {
          echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
      }
    } else {
      echo json_encode( array(
        'success' => false,
        'message' => 'Account or Email already used.',
      ) );
    }
} 
?>