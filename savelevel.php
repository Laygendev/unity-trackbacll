<?php
require_once( './config.php' );
try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}

$hash = $_GET['hash'];
$data = json_decode( $_GET['data'], true );
$data = json_encode( $data['blocs'] );
$realHash = md5($_GET['name'] . $_GET['time'] . $secretKey); 
if($realHash == $hash) { 
  if ( ! empty( $_GET['id'] ) ){
    $sth = $dbh->prepare('UPDATE levels SET data=:data WHERE id=:id');
    try {
        $sth->execute( array( 'id' => $_GET['id'], 'data' => $data ) );
    } catch(Exception $e) {
    }
  } else {
    $sth = $dbh->prepare('INSERT INTO levels VALUES (null, :id_user, :level_name, :times, :data)');
    try {
        $sth->execute( array( 'id_user' => 1, 'level_name' => $_GET['name'], 'times' => '0', 'data' => $data ) );
    } catch(Exception $e) {
    }
  }
  
  echo json_encode( array(
    'success' => true,
  ) );
}
?>
