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
    $sth = $dbh->prepare('SELECT * FROM levels WHERE id=:id_level');
    try {
        $sth->execute( array( 'id_level' => $_GET['id_level'] ));
        
        $data = $sth->fetch();
          echo json_encode(array(
            'success' => true,
            'level_name' => $data['level_name'],
            'data' => json_decode( $data['data']) ,
          ));
    
    } catch(Exception $e) {
        echo '<h1>An error has ocurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }
} 
?>