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
$admincode = admin.$userId;
$getdeals = "SELECT * FROM $admincode WHERE Active = 1";
$deals = mysqli_query($dbconnect, $getdeals);
if ($deals != "") {
    if (mysqli_num_rows($deals)!= 0){
        $a = 1;
        $dbsuccess = "Your Deals"; 
    } else {
    $dbErr2 = "Deals Empty";
    }
} else { 
    $dbErr1 = "Couldn't Display";
}
$getrate = "SELECT `AdAvRate` FROM `login` WHERE `id` ='$userId' LIMIT 1";
$rate = mysqli_fetch_assoc(mysqli_query($dbconnect, $getrate));
$adrate = $rate['AdAvRate'];
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
                    <h4>Admin Rating: <b><?php echo $adrate; ?></b></h4>
                    <h6><br />Participants in your Deals will give</h6>
                    <h6>you a rating once the Deal is completed</h6><br/><br/>
                    <h4>Complete Deals to earn Cred</h4>
                </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2";?></h2><br />
                    <table border ="0" style="">
                        <?php if ($a == 1){
                        echo "<thead><td><h4>UPC  </h4></td><td><h4>Product  </h4></td></td><td><h4>Case  </h4></td><td><h4>Units  </h4></td><td><h4>Price  </h4></td><td><h4>Spaces  </h4></td><td><h4>Details  </h4></td><td><h4>Participants</h4></td><td><h4>Delete?</h4></td></thead>"; 
                            while($row = mysqli_fetch_assoc($deals)) {
                                $itemId = $row["ItemId"];
                                $dealId = $row["DealId"];
                                $getlib = "SELECT * FROM upclib WHERE ID = '$itemId'";
                                $lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
                                $libid = $lib["ID"]; $upc = $lib["UPC"];
                                $case = $lib["CaseSize"]; $itemname = $lib["ItemName"];
                                $itemcode = item.$itemId;
                                $getinfo = "SELECT * FROM `{$itemcode}` WHERE ID = '$dealId'";
                                $info = mysqli_query($dbconnect, $getinfo);
                                while($row2 = mysqli_fetch_assoc($info)) {
                                    echo "<tr><td><h6><br /><a href='ItemPage.php?id=" .$libid. "'>".$upc."</a></h6></td>";
                                    echo "<td><h6><br />".$itemname."</h6></td>";
                                    echo "<td><h6><br />".$case."</h6></td>";
                                    echo "<td><h6><br />".$row2["Quantity"]."</h6></td>";
                                    echo "<td><h6><br />$".$row2["Price"]."</h6></td>";
                                    echo "<td><h6><br />".$row2["Spaces"]."</h6></td>";
                                    echo "<td><h6><br /><a href='DealPage.php?item=".$itemId."&id=".$dealId."'>Details</h6></td>";
                                    echo "<td><h6><br /><a href='Participants.php?item=".$itemId."&id=".$dealId."'>Participants</h6></td>";
                                    echo "<td><h6><br /><a href='DeleteDeal.php?item=".$itemId."&id=".$dealId."'>Delete?</h6></td></tr>";
                                }
                            }
                        }
                        ?>
                    </table>
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

