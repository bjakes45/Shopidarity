<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $info = "";
} else {
    header("Location:index.php");
    die();
}
$cup = cup.$userId;
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
                        <li><a href="#">Library</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>The UPC Library</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="ViewLibrary.php">UPC Library</a></li>
                    <li><a href="SearchLib.php">Search</a></li>
                    <li><a href="Matches.php">Matches</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <br /><br /><br />
                    <h6>Browse the UPC Library and see the Products already in our Database.</h6><br />
                    <br /><br /><h6><br />Search the Library for specific Products.</h6><br />
                    <br /><br /><h6><br />Get suggestions for Products and Deals</h6><br />                    
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


