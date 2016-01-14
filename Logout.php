<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
session_destroy();

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
            <div id="Header"><div style="float:left"><a href="index.php"><img src="Logo.png" height="60" width="60"></a></div><div style="padding-top:13px"><h1><a href="index.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="Login.php">Login</a></li>
                        <li><a href="Register.php">Register</a></li>
                    </ul>
                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Successfully logged out!</h1>
                </div>
                <div id="ContentLeft">
                    <ul><li><a href="index.php">Home</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <div style="margin-left: 150px; margin-top: 20px;"><h1>See you again soon!</h1>
                        <div style="margin-left: 40px; margin-top: 20px;"><img src="wink.png"></div></div>
                </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>
