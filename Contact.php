<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $login = 1;
} else {
    $login = 0;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title>SHOPIDARITY | Share & Save</title>
    </head>
    <body>
        <div id="Holder">
            <div id="Header"><div style="float:left"><a href="Account.php"><img src="Logo.png" height="60" width="60"></a></div>
                <div style="padding-top:13px"><h1><a href="Account.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <?php if ($login == 0){
                    echo "<ul>
                        <li><a href='Login.php'>Login</a></li>
                        <li><a href='Register.php'>Register</a></li>
                    </ul>";
                    } else {
                        echo "<ul>
                        <li><a href='Account.php'>Profile</a></li>
                        <li><a href='Cupboard.php'>Cupboard</a></li>
                        <li><a href='Library.php'>Library</a></li>
                        <li><a href='Logout.php'>Logout</a></li>
                    </ul>";
                    }
                    ?>
                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Contact Us</h1>
                </div>
                <div id="ContentLeft">
                    <h2>Hope you enjoy the site</h2>
                    
                </div>
                <div id="ContentRight"><br />
                    <br /><h2>Feel free to reach us at:</h2>
                    <br /><h4>contact@shopidarity.com</h4>
                </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>

