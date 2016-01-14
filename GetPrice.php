<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["p"])) {
    $p = strip_tags($_GET["p"]);
if (isset($_GET["q"])) {
    $q = strip_tags($_GET["q"]);
    if ($p == 1){
        echo "<h4>Your cost: $".$p*$q."</h4>";
    }else{
echo "<h4>Coming Soon..only 1 Space each for now.</h4>";
    }
} else {
    echo "noqe";
}
}else {
    echo "nope";
}
