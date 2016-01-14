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
if (isset($_GET["id"])) {
    $adminId = strip_tags($_GET["id"]);
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $deals = "";
    $getadmin = "Select * FROM login WHERE id = '$adminId'";
    $admin = mysqli_fetch_assoc(mysqli_query($dbconnect, $getadmin));
    $adminname = $admin["username"]; $adrate = $admin["AdAvRate"];
    $tcred = $admin['TotalCred']; $scred = $admin['SpentCred'];
    $admincode = admin.$adminId;
    $getdeals = "SELECT * FROM `{$admincode}` WHERE `Active` = 1";
    $deals = mysqli_query($dbconnect, $getdeals);
    if ($deals != "") {
        if (mysqli_num_rows($deals)!= 0){
            $a = 1;
            $dbsuccess = "Current Deals"; 
        } else {
        $dbErr2 = "Deals Empty";
        }
    } else { 
        $dbErr1 = "Couldn't Display";
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
                    <h1>Admin: <?php echo $adminname; ?></h1>
                </div>
                <div id="ContentLeft">
                    <h4>Cred: <b><?php echo $tcred-$scred; ?></b></h4>
                    <h6><a href='FAQ.php'>What is Cred?</a></h6>
                    <h4><br />Admin Rating: <b><?php echo $adrate; ?></b></h4>
                    <h6>Admins are only Rated after</h6>
                    <h6>Completing Deals with them.</h6>
                </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2"; ?></h2><br />
                    <table border ="0" style="">
                        <?php if ($a == 1){
                        echo "<thead><td><h4>UPC  </h4></td><td><h4>Product  </h4></td><td><h4>Manufacturer</h4></td><td><h4>Units  </h4></td><td><h4>Price  </h4></td><td><h4>Vendor</h4></td><td><h4>Spaces</h4></td><td><h4>Join?</h4></td></thead>"; 
                            while($row = mysqli_fetch_assoc($deals)) {
                                $itemId = $row["ItemId"];
                                $dealId = $row["DealId"];
                                $getlib = "SELECT * FROM upclib WHERE ID = '$itemId'";
                                $lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
                                $libid = $lib["ID"]; $upc = $lib["UPC"];
                                $itemname = $lib["ItemName"];
                                $manu = $lib["Company"];
                                $manuId = $lib["ManuId"];
                                $itemcode = item.$itemId;
                                $getinfo = "SELECT * FROM `{$itemcode}` WHERE ID = '$dealId'";
                                $info = mysqli_query($dbconnect, $getinfo);
                                while($row2 = mysqli_fetch_assoc($info)) {
                                    echo "<tr><td><h6><br /><a href='ItemPage.php?id=" .$libid. "'>".$upc."</a></h6></td>";
                                    echo "<td><h6><br />".$itemname."</h6></td>";
                                    echo "<td><h6><br /><a href='ManuPage.php?id=".$manuId."'>".$manu."</a></h6></td>";
                                    echo "<td><h6><br />".$row2["Quantity"]."</h6></td>";
                                    echo "<td><h6><br />$".$row2["Price"]."</h6></td>";
                                    echo "<td><h6><br /><a href='VendorPage.php?id=".$row2['VendId']."'>".$row2["Vendor"]."</a></h6></td>";
                                    echo "<td><h6><br />".$row2["Spaces"]."</h6></td>";
                                    echo "<td><h6><br /><a href='DealPage.php?item=".$itemId."&id=".$dealId."'>Join?</h6></td>";
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
