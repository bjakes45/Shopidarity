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
} else {
    header("Location:index.php");
    die();
}
//validate
if (isset($_GET["item"])) {
    $itemId = strip_tags($_GET["item"]);
}
if (isset($_GET["id"])) {
    $dealId = strip_tags($_GET["id"]);
}
if ($itemId != "" && $dealId != "") {
    $itemcode = item.$itemId;
    $getdeal = "SELECT `PointPerson`, `AdminId`, `Complete` from `{$itemcode}` WHERE `ID` = '$dealId' LIMIT 1";
    $deal = mysqli_fetch_assoc(mysqli_query($dbconnect, $getdeal));
    $point = $deal['PointPerson']; $adminId = $deal['AdminId'];
    $dealcode = deal.$itemId._.$dealId;
    $getparts = "SELECT * from `{$dealcode}` WHERE `Approved` != '0'";
    $numparts = mysqli_num_rows(mysqli_query($dbconnect, $getparts));
    $getdone = "SELECT * from `{$dealcode}` WHERE `Approved` = '2'";
    $numdone = mysqli_num_rows(mysqli_query($dbconnect, $getdone));    
}
$com = $deal['Complete'];
if ($userId != $adminId){
    $doneyet = "SELECT * FROM `{$dealcode}` WHERE `Approved` = '2' AND `UserId` = '$userId' LIMIT 1";
    if (mysqli_num_rows(mysqli_query($dbconnect, $doneyet))!= 1){
        $com++;
        $setcom = "UPDATE `{$itemcode}` SET `Complete` = '$com' WHERE `ID` = '$dealId'";
        if (mysqli_query($dbconnect, $setcom)){
            $setapp = "UPDATE `{$dealcode}` SET `Approved` = '2' WHERE `UserId` = '$userId'";
            if (mysqli_query($dbconnect, $setapp)){
                $joincode = join.$userId;
                $comjoin = "UPDATE `{$joincode}` SET `Approved` = '2' WHERE `UserId` = '$userId'";
                if (mysqli_query($dbconnect, $comjoin)){
                    $dbsucess = "You have completed the deal!";
                } else {
                    $dbErr1 = "Couldn't change approval";            
                }
            } else {
                $dbErr1 = "Deal still in Deal Center";
            }
        } else {
            $dbErr1 = "Completion not logged";
        }
    } else {
        $dbErr1 = "You're already done.";
    }
} else if(($numdone+1) > $com){
    $com++;
    $setcom = "UPDATE `{$itemcode}` SET `Complete` = '$com' WHERE `ID` = '$dealId'";
    if (mysqli_query($dbconnect, $setcom)){
        $dbsuccess = "Completion logged";
    } else {
        $dbErr1 = "Admin completion error";
    }
} else {
    $dbErr1 = "You're already done.";
}
if ($com == ($numparts+1)){
    $fin = 1;
} else {
    $fin = 0;
}
if ($fin == 1){
            
    $getcred = "SELECT * FROM `login` WHERE `id` = '$adminId' LIMIT 1";
    $cred = mysqli_fetch_assoc(mysqli_query($dbconnect, $getcred));
    $tcred = $cred['TotalCred'];
    $scred = $cred['SpentCred'];
    $usrate = $cred['AvRate'];
    $adrate = $cred['AdAvRate'];
    $calcred = ($adrate + $usrate)/2;
    $newcred = $tcred + $calcred;
    $credcode = cred.$adminId;
    $givecred = "INSERT INTO `{$credcode}` (`CredIn`, `MgrId`, `Reason`) VALUES ('$calcred', '0', 'Deal Completed')";
    if (mysqli_query($dbconnect, $givecred)){
        $bankcred = "INSERT INTO `bank` (`NewCred`, `UserId`, `MgrId`, `Reason`) VALUES('$calcred', '$adminId', '0', 'Deal Completed');";
        if (mysqli_query($dbconnect, $bankcred)){
            $itemcode = item.$itemId;
            $delit = "UPDATE`{$itemcode}` SET `Active` = '0' WHERE `ID` = '$dealId'";
            if (mysqli_query($dbconnect, $delit)){
                $admincode = admin.$adminId;
                $delad = "UPDATE`{$admincode}` SET `Active` = '0' WHERE `ItemId` = '$itemId' AND `DealId` = '$dealId'";
                if (mysqli_query($dbconnect, $delad)){
                    $com++;
                    $setcom = "UPDATE `{$itemcode}` SET `Complete` = '$com' WHERE `ID` = '$dealId'";
                    if (mysqli_query($dbconnect, $setcom)){
                        $dbErr1 = "Everyone is done!";
                    } else {
                        $dbErr1 = "Not Complete";
                    }
                } else { 
                    $dbErr1 = "Deal not forgotten";
                }
            } else { 
                $dbErr1 = "Deal not gone";
            }
        } else { 
            $dbErr1 = "Cred not banked";
        } 
    } else { 
        $dbErr1 = "Cred not given";
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
                <div id="PageHeading"><h1>Deal Finishing</h1></div>
                <div id="ContentLeft">
                    <h2>You're Done!</h2>
                    <?php if ($fin == 0){
                        echo "<h6><br />When everyone is finished,</h6><h6>the Admin will receive their Cred</h6>";
                    } else {
                        echo "<h6><br />The Admin will now be sent their Cred</h6>";
                    }
                    echo $calcred;
                    ?>
                </div>
                <div id="ContentRight"><br />
                    <h4><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2; ?></h4><br />
                    <table>
                    <?php
                    echo "<tr><td><h6>".$point."</h6></td><td><h6>";
                    if($com == ($numdone+1)){echo "DONE!";}
                        else if ($com > ($numparts)){echo "DONE!";}
                        else {echo "Waiting";}
                    echo "</h6></td></tr>";
                    $cycle = mysqli_query($dbconnect, $getparts);
                    while ($parts = mysqli_fetch_assoc($cycle)){
                        $partId = $parts['UserId'];
                        $getinfo = "SELECT * FROM `login` WHERE `id` = '$partId'";
                        $pinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $getinfo));
                        echo "<tr><td><h6>".$pinfo['username']."</h6></td><td><h6>";
                        if ($parts['Approved'] == 2){echo "DONE!";}
                        else {echo "Waiting";}
                        echo "</h6></td></tr>";
                    }
                    ?>
                    </table>
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
        </div>
    </body>
</html>

