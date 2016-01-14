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
        if ($adminstatus[0]==1) {
            $admin = 1;
        } else if ($adminstatus[0] == 2){
            $admin = 2;
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
$nogo = "";
if (isset($_GET["id"])) {
    $itemId = strip_tags($_GET["id"]);
    $getinfo = "SELECT * FROM upclib WHERE id = '$itemId' LIMIT 1";
    $info = mysqli_query($dbconnect, $getinfo);
    if ($info != "") {
        if (mysqli_num_rows($info)!= 0){
            $dbsuccess = "Product Information";
            $row = mysqli_fetch_assoc($info);
        } else {
            $dbErr2 = "Info Empty?";
        }
    } else { 
        $dbErr1 = "Couldn't Display Information";
    }
    $itempage = item.$itemId;
    $getdeals = "SELECT * from `{$itempage}` WHERE `Active` = '1' AND `Complete` < `Spaces`";
    $deals = mysqli_query($dbconnect, $getdeals);
    if ($deals != "") {
        if (mysqli_num_rows($deals)!= 0){
            $d = 1;
            $dbsuccess2 = "Available Deals:";
        } else {
            $dbErr3 = "No Deals";
        }
    } else {
        $dbErr4 = "Couldn't connect to deals";
    }
    $checkpoint = "SELECT * FROM `{$itempage}` WHERE Active = '1' AND PointPerson = '$username'";
    if (mysqli_num_rows(mysqli_query($dbconnect, $checkpoint))!=0){
        $nogo = 1;
    }
}
$case = $row["CaseSize"];
$cupcode = cup.$userId; $c = 1;
$getcup = "SELECT * FROM `{$cupcode}` WHERE LibId = '$itemId' LIMIT 1";
if (mysqli_num_rows(mysqli_query($dbconnect, $getcup))) {
    $c = 0;
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
                    <h1><?php echo $row['UPC'].": ". $row['ItemName']; ?></h1>
                </div>
                <div id="ContentLeft">
                    <div id="CompanyPic"><img src="Default.png" alt="Product Image"></div>
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2>
                    <br /><h4>Rating: <b><?php echo $row["AvRate"]; ?></b></h4>
                    <?php
                    if ($c == 1){echo "<tr><td><h6><a href = 'AddItem.php?id=".$row['ID']."'>Add?</a></h6></td>";}
                            else {echo "<tr><td><h6><a href = 'RateItem.php?id=".$row['ID']."'>Rate Item</a></h6></td>";}
                    ?>
                    <br />
                    <table>
                        <?php 
                        while($row != '') {
                            echo "<tr><td><h6><b>UPC:</b></h6></td><td><h6>".$row["UPC"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Product:</b></h6></td><td><h6><br />".$row["ItemName"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Case Size:</b></h6></td><td><h6><br />".$case."</h6></td>";
                            echo "<tr><td><h6><br /><b>Manufacturer:</b></h6></td><td><h6><br /><a href = 'Manupage.php?id=".$row["ManuId"]."'>".$row["Company"]."</a></h6></td>";
                            echo "<tr><td><h6><br /><b>Category:</b></h6></td><td><h6><br />".$row["Category"]."</h6></td>";
                            $row= '';
                        }
                        ?> 
                    </table><br/>
                    <h6>Note: If the number of Spaces in a Deal is one,</h6>
                    <h6>the Admin has generously decided to share it</h6>
                    <h6>even though they were able to take</h6>
                    <h6>advantage of the Deal by themselves.</h6>
                    <br />
                    <h6><a href='Contact.php'>Report Error</a></h6>
                </div>
                <div id="ContentRight">
                    <h2><?php echo $dbsuccess2; echo $dbErr3; echo $dbErr4;?></h2><br />
                    <?php 
                    if ($admin ==1) {
                        if ($nogo == 1){
                            echo "<h6>Only one Deal per Item per Admin</h6>";
                            echo "<h6><br />Complete or Delete the current one from your <a href ='AdminDeals.php'>Admin</a> Section </h6>";
                            echo "<h6>if you have another Deal you would rather Share!</h6>";
                        } else {
                        echo "<h6><a href='NewDeal.php?id=".$itemId."'>Create a New Deal</a></h6>";
                        }
                    } else if ( $admin == 2){
                        echo "<h6>Only Admins may Create Deals</h6>";
                        echo "<h6>Mods may still participate in deals if they wish.</h6>";
                    } else {
                        echo "<h6>Only Admins may Create Deals</h6>";
                        echo "<h6>Request permission from your <a href ='Account.php'>Profile</a></h6>";
                    }
                    ?>
                    <h6><br /><a href='#'>Search Deals</a></h6>
                    <br />
                <table>
                        <?php if ( $d == 1){
                        $i = 0;
                        echo "<thead><td><h4>Units  </h4></td><td><h4>Price  </h4></td><td><h4>Spaces  </h4></td><td><h4>$/piece  </h4></td><td><h4>Location  </h4></td><td><h4>Vendor  </h4></td><td><h4>Admin  </h4></td><td><h4>Join?</h4></td></thead>";
                            while($row2 = mysqli_fetch_assoc($deals)) {
                                $units = $row2["Quantity"];
                                $point = $row2["PointPerson"];
                                 
                                $pieces = $case * $units;
                                $unitprice = $row2["Price"] / number_format($pieces, 2);
                                $piececost = number_format(round($unitprice, 2), 2);
                                echo "<tr><td><h6><br />".$units."</h6></td><td><h6><br />$".$row2["Price"]."</h6></td><td><h6><br />".$row2["Spaces"]."</h6></td><td><h6><br />$".$piececost."</h6></td><td><h6><br />".$row2["Location"]."</h6></td><td><h6><br /><a href = 'VendorPage.php?id=".$row2['VendId']."'>".$row2["Vendor"]."</a></h6></td><td><h6><br /><a href = 'AdminPage.php?id=".$row2["AdminId"]."'>".$point."</a></h6></td>";
                                if ($c != 1){ echo "<td><h6><br /><a href='DealPage.php?item=".$itemId."&id=".$row2['ID']."'>Join?</h6></td></tr>";}
                                else { echo "<td><h6><br />Add First</h6></td></tr>";}
                                $i++;
                                if($username == $point){
                                    $nogo = 1;
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

