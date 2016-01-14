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
if(isset($_GET["item"])){
    $itemId = strip_tags($_GET["item"]);
}
if(isset($_GET["id"])){
    $dealId = strip_tags($_GET["id"]);
}
$dealcode = deal.$itemId._.$dealId;
$getmembs = "SELECT * FROM `{$dealcode}` WHERE `Approved` != '0'";
$membs = mysqli_query($dbconnect, $getmembs);
if ($membs != "") {
    if (mysqli_num_rows($membs)!= 0){
        $b = 1;
        $dbsuccess1 = "Present Participants:";
    } else {
    $dbErr4 = "No Participants yet";
    }
} else { 
    $dbErr3 = "Couldn't Display";
}
$itemcode = item.$itemId;
$getinfo = "SELECT * FROM `{$itemcode}` WHERE `ID` = '$dealId' LIMIT 1";
$getlib = "SELECT * FROM `upclib` WHERE `ID` = '$itemId' LIMIT 1";
$info = mysqli_fetch_assoc(mysqli_query($dbconnect, $getinfo));
$lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
$upc = $lib["UPC"]; $product= $lib["ItemName"]; 
$case = $lib["CaseSize"]; $manuId = $lib["ManuId"]; 
$company = $lib["Company"]; $location = $info["Location"];
$units = $info["Quantity"]; $price = $info["Price"]; 
$spaces = $info["Spaces"]; $vendor = $info["Vendor"];
$com =$lib['Complete'];
if (mysqli_num_rows($membs)== $spaces){
    $dbsuccess1 = "Deal Full!";
    $a = 0;
}
$getreqs = "SELECT * FROM `{$dealcode}` WHERE Approved = '0'";
$req = mysqli_query($dbconnect, $getreqs);
if ($req != "") {
    if (mysqli_num_rows($membs) < ($spaces-1)) {
        if (mysqli_num_rows($req)!= 0){
            $a = 1;
             $dbsuccess = "Participant Requests:";
        } else {
        $dbErr2 = "No Requests";
        }
    } else {
        $dbErr2 = "Deal Full!<br /> <a href = 'Dealpage.php?item=".$itemId."&id=".$dealId."'>Complete the Deal Now!</a>";
    }
} else { 
    $dbErr1 = "Couldn't Display";
}
$fin = 0;
if ($com == $spaces){
    $fin = 1;
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
                        <?php if ($admin == 1){echo "<li><a href='Admin.php'>Admin</a></li>";} ?>
                    </ul>
                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1><?php echo $username; ?>'s Admin</h1>
                </div>
                <div id="ContentLeft">
                    <table>
                    <?php
                    echo "<tr><td><h6><b><br />UPC:</b></h6></td><td><h6><br /><a href='ItemPage.php?id=" . $itemId . "''>".$upc."</a></h6></td></tr>";    
                    echo "<tr><td><h6><b><br />Product:</b></h6></td><td><h6><br />".$product."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Case Size:</b></h6></td><td><h6><br />".$case."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Manufacturer:</b></h6></td><td><h6><br /><a href = 'ManuPage.php?id=".$manuId."'>".$company."</a></h6></td></tr>";
                    echo "<tr><td><h6><b><br />Units:</b></h6></td><td><h6><br />".$units."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Price:</b></h6></td><td><h6><br />$".$price."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Spaces:</b></h6></td><td><h6><br />".$spaces."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Location:</b></h6></td><td><h6><br />".$location."</h6></td></tr>";
                    echo "<tr><td><h6><b><br />Vendor:</b></h6></td><td><h6><br />".$vendor."</h6></td></tr>"; 
                    ?>
                    </table>
                </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess1"; echo "$dbErr3"; echo "$dbErr4"; ?></h2><br />
                    <table border ="0" style="">
                    <?php 
                    if ($b == 1){
                        echo "<thead><td><h4>Username</h4></td><td><h4>Purchases</h4></td>";
                        if ($com == 1){
                            echo "<td><h4>Drop?</h4></td></thead>";
                        }
                        while($row1 = mysqli_fetch_assoc($membs)) {
                            $membname = $row1["Username"];
                            $mempurfreq = $row1["PurFreq"];
                            echo "<tr><td><h6><br />".$membname."</h6></td><td><h6><br />".$mempurfreq."</h6></td>";
                            if ($com == 1) {
                                echo "<td><h6><a href='ConfirmDrop.php'>Drop?</a></h6></td>";
                            }
                        }
                    }
                    ?>
                    </table>
                    <br />
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2"; ?></h2><br />
                    <table border ="0" style="">
                    <?php 
                    if ($a == 1){
                        echo "<thead><tr><td><h4>Username</h4></td><td><h4>Purchases</h4></td><td><h4>Accept?</h4></td></tr></thead>";
                        while($row = mysqli_fetch_assoc($req)) {
                            $reqname = $row["Username"];
                            $purfreq = $row["PurFreq"];
                            echo "<tr><td><h6><br />".$reqname."</h6></td><td><h6><br />".$purfreq."</h6></td><td><h6><br /><a href = 'ConfirmJoin.php?item=".$itemId."&id=".$dealId."&u=".$row['UserId']."'>Accept?</a></h6></td></tr>";
                        }
                    }
                    ?>
                    </table>
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
