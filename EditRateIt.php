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
        if ($adminstatus[0]) {
            $admin = 1;
        } else {
            $admin = 0;
        }
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $dbsuccess2 = ""; $dbErr3 = ""; $dbErr4 = "";
    $info = ""; $deals = "";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET['id'])){
    $itemId = strip_tags($_GET['id']);
    $ratecode = rateit.$itemId;
}
if (isset($_POST['Submit'])){
    $delrate = "UPDATE `{$ratecode}` SET `Delete` = '1' WHERE `UserId` = '$userId' AND `Delete` = '0' LIMIT 1";
    $del = mysqli_query($dbconnect, $delrate);
    if($del){
        $dbsuccess = "Rating Deleted. Pleae Rate the Item again.";
    } else{
        $dbErr1 = "Didn't work.";
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
                     <h1>Edit Ratings</h1>
                </div>
                <div id="ContentLeft">
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2>
                    <br/>
                </div>
                <div id="ContentRight">
                    <form action ="EditRateIt.php?id=<?php echo $itemId;?>" method ="POST">
                    <h6>Your old Rating will be deleted.</h6>
                    <br /><input type='submit' name='Submit' value='Proceed'/></form>
                            <h6><br />Return to <a href="RateItem.php?id=<?php echo $itemId;?>">Item Rating</a>
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

