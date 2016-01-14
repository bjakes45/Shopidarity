<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
} else {
    header("Location:index.php");
    die();
}
$dealcode = deal.$itemId._.$dealId;
$getraff = "SELECT * FROM `{$dealcode}` WHERE `Raffwin` !> '0'";
$raffyet = 0;
if(mysqli_query($dbconnect, $getraff) != 0){
    $raffyet = 1;
}
$joincode = join.$userId;
$getdeals = "SELECT  * FROM `{$joincode}` WHERE `Active` = '1' AND `Approved` != '2'";
$deals = mysqli_query($dbconnect, $getdeals);
if ($deals != "") {
    if (mysqli_num_rows($deals)!= 0){
        $a = 1;
        $dbsuccess = "Deals you've requested to participate in"; 
    } else {
    $dbErr2 = "Deals Empty";
    }
} else { 
    $dbErr1 = "Couldn't Display";
}
$getrate = "SELECT AvRate FROM login WHERE id= '$userId'";
$rate = mysqli_fetch_assoc(mysqli_query($dbconnect, $getrate))
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
                    <h1><?php echo $username; ?>'s Deals</h1>
                </div>
                <div id="ContentLeft">
                    <h4>User Rating: <b><?php echo $rate['AvRate']?></b></h4>
                    <br /><h6><br />Admins that host Deals that you join</h6>
                    <h6>Will give you a Rating when the Deal is Complete.</h6>
                    <br /><h6><br />Higher Ratings will get you Accepted into more Deals!</h6>
                </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2"; ?></h2><br />
                    <table border ="0" style="">
                        <?php if ($a == 1){
                        echo "<thead><td><h4>UPC  </h4></td><td><h4>Product  </h4></td><td><h4>Case</h4></td><td><h4>Units  </h4></td><td><h4>Price  </h4></td><td><h4>Spaces  </h4></td><td><h4>Details</h4></td><td><h4>Accepted?</h4></td><td><h4>Quit?</h4></td></thead>"; 
                            while($row = mysqli_fetch_assoc($deals)) {
                                $itemId = $row["ItemId"];
                                $dealId = $row["DealId"];
                                if ($row["Approved"] == 1){
                                    $approv = "Yes";
                                } else {
                                    $approv = "No";
                                }
                                $getlib = "SELECT * FROM upclib WHERE ID = '$itemId'";
                                $lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
                                $libid = $lib["ID"]; $upc = $lib["UPC"]; $case = $lib["CaseSize"];
                                $itemname = $lib["ItemName"];
                                $itemcode = item.$itemId;
                                $getinfo = "SELECT * FROM `{$itemcode}` WHERE ID = '$dealId'";
                                $info = mysqli_query($dbconnect, $getinfo);
                                while($row2 = mysqli_fetch_assoc($info)) {
                                    echo "<tr><td><h6><br /><a href='ItemPage.php?id=" .$libid. "'>".$upc."</a></h6></td>";
                                    echo "<td><h6><br />".$itemname."</h6></td>";
                                    echo "<td><h6><br />".$case."</h6></td>";
                                    echo "<td><h6><br />".$row2["Quantity"]."</h6></td>";
                                    echo "<td><h6><br />".$row2["Price"]."</h6></td>";
                                    echo "<td><h6><br />".$row2["Spaces"]."</h6></td>";
                                    echo "<td><h6><br /><a href='DealPage.php?item=".$itemId."&id=".$dealId."'>Details</h6></td>";
                                    echo "<td><h6><br />".$approv."</td>";
                                    if ($row2['Complete']==0){
                                        echo "<td><h6><br /><a href='ConfirmDQuit.php?item=".$itemId."&id=".$dealId."'>Quit?</h6></td>";
                                    } else if ($raffyet == 1){
                                        echo "<td><h6>Must Complete</h6></td>";

                                    } else {
                                        echo "<td><h6>Must Complete</h6></td>";
                                    }
                                    echo "</tr>";
                                }
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

