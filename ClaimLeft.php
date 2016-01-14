<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $admin = 2;
    $getadmin = "SELECT * FROM `login` WHERE `id` ='$userId' LIMIT 1";
    $adminstatus = mysqli_fetch_assoc(mysqli_query($dbconnect, $getadmin));
    if ($adminstatus['admin']== 1) {
        $admin = 1;
    } else {
        $admin = 0;
    }
    $tcred = $adminstatus['TotalCred'];
    $scred = $adminstatus['SpentCred'];
    $acred = ($tcred-$scred);
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
 $itemId = ""; $dealId = ""; $deal = "";
    $info = ""; $join = "0";
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
$joincode = join.$userId;
if ($itemId != "" && $dealId != "") {
        $item = item.$itemId;
        $sql = "Select * FROM `{$item}` WHERE ID = '$dealId' LIMIT 1";
        $deal = mysqli_query($dbconnect , $sql);
        if ($deal != "") {
            if (mysqli_num_rows($deal)!= 0) {
                $dbsuccess = "Claiming Leftovers";
                $row = mysqli_fetch_assoc($deal);
                $query = "SELECT * FROM upclib WHERE ID = '$itemId'";
                $info = mysqli_query($dbconnect, $query);
                if ($info != "") {
                   if (mysqli_num_rows($info)!= 0){
                        $dbsuccess2 = "The Breakdown:";
                        $row2 = mysqli_fetch_assoc($info);
                        $dealcode = deal.$itemId._.$dealId;
                        $jsql = "SELECT * FROM `{$dealcode}` WHERE Approved != 0";
                        $join = mysqli_query($dbconnect, $jsql);
                        if ($join != "0") {
                            if (mysqli_num_rows($join)!= 0){
                                $dbsuccess3 = "Current Participants:";
                            } else {
                                $dbErr5 = "Be the first to join the Admin in this Deal!";
                            }
                        } else {
                            $dbErr6 = "That's strange";
                        }
                    } else {
                        $dbErr3 = "Product Info Empty";
                    }
                } else {
                    $dbErr4 = "Couldn't Display Product Info";
                }
            } else {
                $dbErr2 = "Deal Info Empty";
            }
        } else {
            $dbErr1 = "Couldn't Display Deal Information";
        }
} else {
    $dbErr1 = "Oops";
}
$units = $row["Quantity"]; $price = $row["Price"]; 
$spaces = $row["Spaces"]; $locationd = $row["Location"]; 
$vendor = $row["Vendor"]; $point = $row["PointPerson"];
$adminId = $row["AdminId"]; $upc = $row2["UPC"]; 
$product = $row2["ItemName"]; $case = $row2["CaseSize"]; 
$company = $row2["Company"]; $manuId = $row2["ManuId"];
$pieces =  $units * $case; $claim = $row['Claim'];
$com = $row['Complete'];
$pieceseach = $pieces/$spaces;
$pieceseach = floor($pieceseach);
$remainder = $pieces % $spaces;
$unclaim = $remainder - $claim;
$moneypieces = number_format($pieces, 2);
$piececost = $price/$moneypieces;
$piececost = round($piececost, 2);
$piececost = number_format($piececost, 2);
$credcost = ceil($piececost*100);
$moneyspaces = number_format($spaces, 2);
$costeach = $price/$moneyspaces;
$costeach = round($costeach, 2);
$costeach = number_format($costeach, 2);
$checkjoin = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId'";
$checkpart = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId' AND `Approved` != '0' LIMIT 1";
$membyet = 0 ;
if (mysqli_num_rows(mysqli_query($dbconnect, $checkpart)) == 1){
    $membyet = 1;
} else if($username == $point) {
    $membyet = 1;
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
                <div id="PageHeading"><h1><?php echo $point?>'s Deal</h1></div>
                <div id="ContentLeft">
                    <table border = '0'>
                        <?php 
                        while($row != '') { 
                            echo "<tr><td><h6><b><br />UPC:</b></h6></td><td><h6><br /><a href='ItemPage.php?id=" . $itemId . "''>".$upc."</a></h6></td>";
                            echo "<tr><td><h6><b><br />Product:</b></h6></td><td><h6><br />".$product."</h6></td>";
                            echo "<tr><td><h6><b><br />Case Size:</b></h6></td><td><h6><br />".$case."</h6></td>";
                            echo "<tr><td><h6><b><br />Manufacturer:</b></h6></td><td><h6><br /><a href='ManuPage.php?id=".$manuId."'>".$company."</a></h6></td>";
                            echo "<tr><td><h6><b><br />Units:</b></h6></td><td><h6><br />".$units."</h6></td>";
                            echo "<tr><td><h6><b><br />Price:</b></h6></td><td><h6><br />$".$price."</h6></td>";
                            echo "<tr><td><h6><b><br />Spaces:</b></h6></td><td><h6><br />".$spaces."</h6></td>";
                            echo "<tr><td><h6><b><br />Location:</b></h6></td><td><h6><br />".$locationd."</h6></td>";
                            echo "<tr><td><h6><b><br />Vendor:</b></h6></td><td><h6><br /><a href='VendorPage.php?id=".$row['VendId']."'>".$vendor."</a></h6></td>";
                            echo "<tr><td><h6><b><br />Admin:</b></h6></td><td><h6><br /><a href = 'AdminPage.php?id=".$adminId."'>".$point."</a></h6></td>";
                            $row= '';
                        }
                        ?> 
                    </table><br /><br />
                    <h6><a href='#'>Report Error</a></h6>
                </div>
                <div id="ContentRight"><br />
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2><br />
                    <h4><?php echo $dbsuccess2; echo $dbErr3; echo $dbErr4; ?></h4><br />
                    <table>
                        <tr>
                            <td><h6>Total Pieces:</h6></td><td><h6><?php echo $pieces; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Pieces Each:</h6></td><td><h6><?php echo $pieceseach; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Piece Cost:</h6></td><td><h6>$<?php echo $piececost; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Cost Each:</h6></td><td><h6>$<?php echo $costeach; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Leftovers:</h6></td><td><h6><?php echo $remainder; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Claimed:</h6></td><td><h6><?php echo $claim; ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6>Raffled:</h6></td><td><h6><?php echo $raffwin; ?></h6></td>
                        </tr>
                    </table><br />
                    <?php
                    if ($remainder != 0){
                        echo "<h4>".$unclaim." Unclaimed Leftovers</h4>
                              <h6><br />Available For: <b>".$credcost." Cred each</b></h6>";
                    }?>
                    <br/>
                    <h4>You have <b><?php echo $acred; ?></b> Cred available</h4>
                    <?php
                    if ($acred < $credcost){
                        echo "<h6><br /><b>Insufficient Cred</b></h6>
                            <h6><br />Earn more by <a href='NewItem.php'>Adding New Items</a> and <a href='AdminDeals.php'>Completing Deals</a></h6>
                            <h6><br />Or you can pay cash to <a href='BuyCred.php'>Buy Cred</a></h6>";
                    } else if ($unclaim == 1){
                        if ($username == $point){
                            echo "<br /><form method='POST' action='ClaimLeft.php?item=".$itemId."&id=".$dealId."'>
                            <input type= 'submit'  name = 'submit' value='Claim One'>
                            </form>";
                        } else {
                            echo "<h6><br />Only the Point Person may Claim the Last Leftover</h6>";
                        }
                    } else {
                        echo "<br /><form method='POST' action='ClaimLeft.php?item=".$itemId."&id=".$dealId."'>
                            <input type= 'submit'  name = 'submit' value='Claim One'>
                            </form>";
                    }
                    echo "<h6><br />Return to <a href='DealPage.php?item=".$itemId."&id=".$dealId."'>Deal Page</a></h6>"
                    ?>
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