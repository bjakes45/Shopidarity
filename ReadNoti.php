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
if (isset($_GET['id'])){
    $notiId = strip_tags($_GET['id']);
    $noticode = noti.$userId;
    $checknoti = "SELECT * FROM `{$noticode}` WHERE ID = '$notiId'";
    $nquery = mysqli_query($dbconnect,$checknoti);
    if ($row = mysqli_fetch_assoc($nquery)){
        $from = $row['FromName'];
        $time = $row['Time'];
        $subject = $row['Subject'];
        $message = $row['Message'];
        $seenoti = "UPDATE `{$noticode}` SET `Seen` = '1' WHERE `ID` = '$notiId'";
        if (mysqli_query($dbconnect, $seenoti)) {
            $dbsuccess = "Notification:";
        } else {
            $dbErr1 = "Notification Not marked as Read.";
        }
    } else {
        $dbErr1 = "Nothing to see here.";
    }
} else {
    $dbErr1 = "Can't get info.";
}
?>
<!DOCTYPE html>

<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id="Holder">
            <div id="Header"><div style="float:left"><a href="Account.php"><img src="Logo.png" height="60" width="60"></a></div>
                <div style="padding-top:13px"><h1><a href="Account.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="Account.php">Profile</a></li>
                        <li><a href="Cupboard.php">Cupboard</a></li>
                        <li><a href="Library.php">Library</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>From: <?php echo $from; ?></h1>
                </div>
                <div id="ContentLeft">
                    <h2><?php echo $subject; ?></h2>
                    <h6><br/><?php echo $time; ?></h6>    
                    
                </div>
                <div id="ContentRight">
                    <br/>
                    <h4><br/><?php echo $message; ?></h4>
                    </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>

