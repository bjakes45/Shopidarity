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
$noti = noti.$userId;
$checknoti = "SELECT * FROM `{$noti}` WHERE `Seen` = '0' AND `Delete` = '0'";
$numnew = mysqli_num_rows(mysqli_query($dbconnect, $checknoti));
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
                        <li><a href="#">Cupboard</a></li>
                        <li><a href="Library.php">Library</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1><?php echo $username; ?>'s Cupboard</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <?php
                        if ($numnew == 0){
                            echo "<li><a href='Notifications.php'>Notifications</a></li>";
                        } else {
                            echo "<li><a href='Notifications.php'><b>Notifications(".$numnew.")</b></a></li>";
                        }
                        ?>
                    <li><a href="ViewItem.php">Your Cupboard</a></li>
                    <li><a href="MyDeals.php">Deal Center</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <br /><br /><br />
                    <h6>Get Alerts from the site.</h6><br /><br /><br />
                    <h6><br />Manage or delete the items you've added to your Cupboard.</h6><br /><br /><br />
                    <h6><br />Check the status of the Deals you Join.</h6>
                </div>
            </div>
            <div id="Footer">
                <br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6>
            </div>
        </div>
    </body>
</html>
