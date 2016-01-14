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
    if ($admin == 0) {
        header("Location:Account.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}
$dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
$deals = "";
if(isset($_GET["item"])){
    $itemId = strip_tags($_GET["item"]);
}
if(isset($_GET["id"])){
    $dealId = strip_tags($_GET["id"]);
}
if(isset($_GET["u"])){
    $partId = strip_tags($_GET["u"]);
}
$itemcode = item.$itemId;
$dealcode = deal.$itemId._.$dealId;
$joincode = join.$partId;
$noticode = noti.$partId;
$partinfo = "SELECT * FROM login WHERE id = '$partId'";
$pinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $partinfo));
$iteminfo = "SELECT * FROM `upclib` WHERE `ID` = '$itemId'";
$itinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $iteminfo));
$dealinfo = "SELECT * FROM `{$itemcode}` WHERE `ID` = '$dealId'";
$dinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $dealinfo));
$spaces = $dinfo['Spaces'];
$partinfo = "SELECT * FROM `{$dealcode}` WHERE `Approved` = '1'";
$numpart = mysqli_num_rows(mysqli_query($dbconnect, $partinfo));
$valid=0;
if (($spaces - $numpart) > 0){
    $valid++;
} else {
    $dbErr1 = "Deal already Full!";
}
if (isset($_POST['Confirm'])){
    $valid++;
}
if ($valid == 2) {
    $apjoin = "UPDATE `{$joincode}` SET `Approved` = '1' WHERE `ItemId` = '$itemId' AND `DealId` = '$dealId'";
    if (mysqli_query($dbconnect, $apjoin)){
            $addjoin = "UPDATE `{$dealcode}` SET `Approved` = '1' WHERE `UserId` = '$partId'";
        if (mysqli_query($dbconnect, $addjoin)){
            $notipart = "INSERT INTO `{$noticode}` (`FromId`, `FromName`, `Subject`, `Message`) VALUES ('$userId', '$username', 'Deal Joined!', 'You have been accepted into a deal. Check it out in you Deal Center.')";
            if (mysqli_query($dbconnect, $notipart)){
                $dbsuccess = "Participant accepted<br />Back to <a href='Participants.php?item=".$itemId."&id=".$dealId."'>Participants Page</a>";
            } else {
                $dbErr2 = "Participant not notified.";
            }
        } else {
            $dbErr2 = "Not added to Deal Page";
        }
    } else {
        $dbErr2 = "Not added to Deal Center";
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
                <div id="PageHeading"><h1>Confirm Participant</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h4><?php echo $dbErr1; echo $dbErr2; echo $dbsuccess; ?></h4><br />
                    <?php if (!isset($_POST["Add"])) { 
                        echo "<h6>Do you really want to allow User = ".$pinfo['username']." to join your deal?</h6><br />
                        <h6>Their Rating: ".$pinfo['AvRate'].".</h6>";
                    }?><br />
                    <form action ="ConfirmJoin.php?item=<?php echo $itemId."&id=".$dealId."&u=".$partId; ?>" method="POST">
                        <input type="submit" name="Confirm" value="Confirm Join"/>
                    </form>
                  </div>
            </div>
            <div id='Footer'><br /><br/>
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