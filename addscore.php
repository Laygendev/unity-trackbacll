<?php
require_once( './config.php' );
try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}

$hash = $_GET['hash'];

$realHash = md5($_GET['name'] . $_GET['time'] . $secretKey); 
if($realHash == $hash) { 
  $sth = $dbh->prepare('SELECT time FROM scores WHERE name=:name AND id_level=:id_level LIMIT 0,1');
  $sth->execute( array( 'name' => $_GET['name'], 'id_level' => $_GET['id_level'] ) );
  $data = $sth->fetch();
  
  $status = true;
  
  if ( ! $data ) {
    $status = add_score($dbh, $_GET['id_level'], $_GET['name'], $_GET['time'] );
  } else {
    $cleanDataTime = (int) str_replace( '.', '', str_replace( ':', '', $data['time'] ) );
    $cleanFormTime = (int) str_replace( '.', '', str_replace( ':', '', $_GET['time'] ) );
    if ( $cleanDataTime > $cleanFormTime ) {
      $sth = $dbh->prepare('DELETE FROM scores WHERE name=:name AND id_level=:id_level');
      $sth->execute( array( 'name' => $_GET['name'], 'id_level' => $_GET['id_level'] ) );
      $status = add_score($dbh, $_GET['id_level'], $_GET['name'], $_GET['time'] );
    }
  }
  
  echo json_encode( array(
    'success' => $status,
  ) );
} 

function add_score( $dbh, $id_level, $name, $time ) {
  $sth = $dbh->prepare('INSERT INTO scores VALUES (null, :id_level, :name, :time)');
  try {
      $sth->execute( array( 'id_level' => $id_level, 'name' => $name, 'time' => $time ) );
      return true;
  } catch(Exception $e) {
    return false;
  }
}
?>
