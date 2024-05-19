<?php 

# server name
$sName = "localhost";
# user name
$uName = "root";
# password
$pass = "";

// #klaudia's 
// $sName = "localhost:3310";
// $uName = "root";
// $pass = "";

# database name
$db_name = "library";


// creating database connection 
// useing The PHP Data Objects (PDO)
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed : ". $e->getMessage();
}