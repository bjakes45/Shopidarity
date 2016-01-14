<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
     $dbsuccess = ""; $dbErr1 =""; $dbErr2 ="";
    $valid = 0;
    $mgmt = 2;
    $getmgmt = "SELECT * FROM `mgmt` WHERE `userId` ='$userId' LIMIT 1";
    $mgmtstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getmgmt));
        if ($mgmtstatus[0]) {
            $mgmt = 1;
            $mgrcode = $mgmtstatus[2];
        } else {
            $mgmt = 0;
        }
    if ($mgmt != 1) {
        header("Location:Account.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}if (isset($_GET["id"])) {
    $vendId = strip_tags($_GET["id"]);
    $getinfo = "SELECT * FROM vendor WHERE id = '$vendId' LIMIT 1";
    $info = mysqli_fetch_assoc(mysqli_query($dbconnect, $getinfo));
    if (!empty($info)) {
        $vendname = $info["VendName"]; $conf = $info["Confirm"];
        $loc = $info["Location"]; $adminId = $info["AdminId"];
        $time = $info["Time"];
        if ($ver == 0){
            $valid++;
        } else {
            $dbErr1 = "Already Confirmed.";
        }
    }
}
if (isset($_POST["Confirm"])) {
    $valid++;
    if (isset($_POST["Code"])) {
        $code = strip_tags($_POST["Code"]);
        if ($code == $mgrcode) {
            $valid++;
        } else {
            $dbErr1 = "Wrong Code.";
        }
        if ($valid == 3) {
            $vendcode = vend.$vendId;
            $makevend = "CREATE TABLE `{$vendcode}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `ItemId` INT(10) NOT NULL, `DealId` INT(10) NOT NULL, `Quantity` INT(8) NOT NULL, `Price` DECIMAL(10,2) NOT NULL, `Spaces` INT(8) NOT NULL, `Location` VARCHAR(30), `PointPerson` VARCHAR(30), `AdminId` INT(10) NOT NULL, `Active` TINYINT DEFAULT '1' NOT NULL,`Complete` TINYINT DEFAULT '0' NOT NULL, `Expiry` VARCHAR(30), PRIMARY KEY(`ID`))";
            if (mysqli_query($dbconnect, $makevend)){
                $ratetable = venrate.$vendId;
                $venrate = "CREATE TABLE `{$ratetable}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `UserId` Int(10) NOT NULL, `Rating` TINYINT(2) DEFAULT '5', `Comment` VARCHAR(144), `Public` TINYINT(1) DEFAULT '1', PRIMARY KEY(`ID`))";
                if (mysqli_query($dbconnect, $venrate)) {
                    $confvend = "UPDATE `vendor` SET `Confirm` = 1, `MgrId` = '$userId' WHERE `ID` = '$vendId'";
                    if (mysqli_query($dbconnect, $confvend)){
                        $dbsuccess = "Vendor made!";
                    } else {
                        $dbErr2 = "Vendor wasn't confirmed";
                    }
                }
            }
        } else {
            $dbErr2 = "Couldn't make Vendor Page.";
        }
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
                <div id="PageHeading"><h1>Confirm Vendor</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h4><?php echo $dbErr1; echo $dbErr2; echo $dbsuccess; ?></h4><br />
                    <h6><br />Is <?php echo $vendname;?> a real Vendor?</h6>
                    <h6><br />Added = <?php echo $time;?></h6>
                    <h6><br />Submitted by AdminId = <?php echo $adminId;?></h6>
                    <br /><br />
                 <form method="POST" action="ConfVend.php?id=<?php echo $vendId ?>">
                        <h6>Manager Code</h6><br/>
                                    <input type= "password" name= "Code" value="" /><br /><br />
                                    <input type="submit" name= "Confirm" value="Verify"><h6><br />or<a href="ConfirmVendor.php">Return to Vendor Confirmation Page</a></h6>            
                    </form>
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
           