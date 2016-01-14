<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $items = "";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["id"])){
    $manuId = strip_tags($_GET["id"]);
    $manuinfo = "SELECT * FROM manufacturer WHERE ID = '$manuId'";
    $minfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $manuinfo));
    $manuname = $minfo["Name"];
    $manucode = manu.$manuId;
    $getitems = "SELECT * from `{$manucode}`";
    $items = mysqli_query($dbconnect, $getitems);
}
if ($items != "") {
    if (mysqli_num_rows($items)!= 0){
        $a = 1;
        $dbsuccess = "Their Items"; 
    } else {
    $dbErr2 = "Products Empty";
    }
} else { 
    $dbErr1 = "Couldn't Display";
}
$canrate = 0;
$cupcode = cup.$userId;
$checkcup = "SELECT * FROM `{$cupcode}` WHERE `ManuId` = '$manuId'";
if (mysqli_num_rows(mysqli_query($dbconnect, $checkcup))!=0){
$canrate = 1;}
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
                    <h1><?php echo $manuname; ?> Products</h1>
                    </div>
                <div id="ContentLeft">
                    <div id="CompanyPic"><img src="Default.png" alt="Product Image"></div>
                    <h4>Rating: <b><?php echo $minfo['AvRate']; ?></b></h4>
                    <?php
                    if ($canrate == 0){
                        echo "<h6><br />You must have at least one of this Manufacturer's</h6>
                        <h6> Products in your Cupboard to be able to rate them.</h6>";
                    } else {
                        echo "<h6><br /><a href='RateManu.php?id=".$manuId."'>Rate Manufacturer.</a></h6>";
                    }
                    ?>
                    <h6><br /><br /><br />Do you represent this Company?</h6>
                    <h6><br /><br/><a href = "#">Contact Us</a> about what content is on this page.</h6>
                    <h6><br />Attract users to make Deals with your Products!</h6>
                    <h6><br/>Add Pictures and Descriptions for your items,</h6>
                    <h6>To make them stand out from your competitors.</h6>
                    <h6><br />Certify your Company for a monthly fee now!</h6>
                    </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2";?></h2><br />
                    <table border ="0" style="">
                        <?php 
                        if ($a == 1){
                            echo "<thead><td><h4>UPC  </h4></td><td><h4>Product  </h4></td><td><h4>Case  </h4><td><h4>Category  </h4></td></thead>"; 
                            while ($row = mysqli_fetch_assoc($items)) {
                                echo "<tr><td><h6><br/><a href='ItemPage.php?id=" . $row["LibId"]. "'>".$row["UPC"]."</a></h6></td>";
                                echo "<td><h6><br/>".$row["ItemName"]."</h6></td>";
                                echo "<td><h6><br/>".$row["CaseSize"]."</h6></td>";
                                echo "<td><h6><br/>".$row["Category"]."</h6></td></tr>";   
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href=Contact.php#'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>  
