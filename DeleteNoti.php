<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $item = ""; $valid = 0;
    $dbsuccess = ""; $dbErr1 ="";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["id"])) {
    $notiId = strip_tags($_GET["id"]);
    $valid++;
}
if(isset($_POST['Delete'])) {
    $noticode = noti.$userId;
    $deletenoti = "UPDATE `{$noticode}` SET `Delete` = '1' WHERE `ID` = '$notiId'";
    If (mysqli_query($dbconnect, $deletenoti)){
        $dbsuccess = "Notification Deleted!";
    } else {
        $dbErr1 = "Couldn't Delete...";
    }
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
                    <h1>Confirm Delete</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h6><br /><?php if (!isset($_POST["Delete"])) { echo "Do you really want to delete this Notification?";} echo $dbErr1;echo $dbErr2; echo $dbsuccess?></h6><br />
                    <form method="POST" action="DeleteNoti.php?id=<?php echo $notiId ?>">
                        <input type="submit" name="Delete" value="Delete"><h6><br />or<a href="Notifications.php">Return to Notifications</h6>
                    </form></div>
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
