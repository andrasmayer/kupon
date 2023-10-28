<?php
$dsn = 'mysql:dbname=kuponos;host=localhost';
$user = 'root';
$password = '';



try {
    $con = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


$discordId =  $_GET["discordId"]; // Ha meggondolod magad
$id = $_GET["id"];




//Kuponkód ellenőrzése státus 0 aktív, vagy 1 aktivált
$sql =  "select status from kuponok where ID = ? ";
$sth	=	$con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute([	$id	]);
$kupon	=	$sth->fetch(\PDO::FETCH_COLUMN,0);


if($kupon === false){   //  Ha nincs találat
    $output =  "A kupon nem létezik";
}
else if($kupon == 1){
    $output =  "A kupot már felhasználták";
}
else{
    $sql =  "update kuponok set status = ?, discordId = ? where ID = ? ";
    $sth	=	$con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute([	1, $discordId, $id	]);

    $output = "Sikeresen aktiváltad a kupont";    
}

echo json_encode($output);


