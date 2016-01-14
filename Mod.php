<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $admin = 2;
    $getadmin = "SELECT `admin` FROM `login` WHERE `id` ='$userId' LIMIT 1";
    $adminstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getadmin));
        if ($adminstatus[0] == 1) {
            $admin = 1;
        } else if ($adminstatus[0] ==2){
            $admin = 2;
        } else {
            $admin = 0;
        }
    if ($admin != 2) {
        header("Location:Account.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
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
                        <?php if ($admin == 2){echo "<li><a href='Admin.php'>Mod</a></li>";} ?>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1><?php echo $username; ?>'s Mod</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="#">Upload Pictures</a></li>                            
                    <li><a href="#">Brand Descriptions</a></li>              
                    <li><a href="#">Renew Certification</a></li>              
                    </ul>   
                </div>
                <div id="ContentRight">
                    <br /><br /><br />
                    <h6>Add Company Logo and other pictures specific to your company.</h6><br />
                    <br /><br /><h6><br />Perfect your image and how your Company is represented on the site.</h6><br />
                    <br /><br /><h6><br />Review your Payment Information and the terms of your Subscription.</h6><br />
                   </div>
            </div>
            <div id="Footer">
                <br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else if($admin == 0){echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6>
            </div>
        </div>
    </body>
</html>


