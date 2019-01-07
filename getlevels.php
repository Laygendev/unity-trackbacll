<?php
// Configuration
require_once( './config.php' );

try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}

$hash = $_GET['hash'];
$levels = array();
$pos = 1;
$realHash = md5($_GET['id_user'] . $secretKey); 
if($realHash == $hash) { 
    $sth = $dbh->prepare('SELECT * FROM levels WHERE id_user=:id_user');
    try {
        $sth->execute( array( 'id_user' => $_GET['id_user'] ));
        
        while ($data = $sth->fetch()) {
          $levels[] = array(
            'id' => $data['id'],
            'level_name' => $data['level_name'],
            'id_user' => $data['id_user'],
            'times' => $data['times'],
          );
        }
        
        echo json_encode( array(
          'success' => true,
          'levels'    => $levels,
        ) );
    
    } catch(Exception $e) {
        echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }
} 
?>