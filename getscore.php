<?php
// Configuration
require_once( './config.php' );

try {
    $dbh = new PDO('mysql:host='. $hostname .';dbname='. $database, $username, $password);
} catch(PDOException $e) {
    echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
}

$hash = $_GET['hash'];
$leaderboard = array();
$pos = 1;
$realHash = md5($_GET['id_level'] . $secretKey); 
if($realHash == $hash) { 
    $sth = $dbh->prepare('SELECT * FROM scores WHERE id_level=:id_level ORDER BY time ASC LIMIT 0,100');
    try {
        $sth->execute( array( 'id_level' => $_GET['id_level'] ));
        
        while( $data = $sth->fetch() ) {
          $leaderboard[] = array(
            'pos' => $pos,
            'pseudo' => $data['name'],
            'time' => $data['time'],
          );
          
          $pos++;
        }
        
        echo json_encode( array(
          'success' => true,
          'data'    => $leaderboard,
        ) );
    } catch(Exception $e) {
        echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }
} 
?>