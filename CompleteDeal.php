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
$joincode = join.$userId;
if ($itemId != "" && $dealId != "") {
        $item = item.$itemId;
        $sql = "Select * FROM `{$item}` WHERE ID = '$dealId' LIMIT 1";
        $deal = mysqli_query($dbconnect , $sql);
        if ($deal != "") {
            if (mysqli_num_rows($deal)!= 0) {
                $dbsuccess = "Deal Completion";
                $row = mysqli_fetch_assoc($deal);
                $query = "SELECT * FROM upclib WHERE ID = '$itemId'";
                $info = mysqli_query($dbconnect, $query);
                if ($info != "") {
                   if (mysqli_num_rows($info)!= 0){
                        $row2 = mysqli_fetch_assoc($info);
                        $dealcode = deal.$itemId._.$dealId;
                        $jsql = "SELECT * FROM `{$dealcode}` WHERE Approved != 0";
                        $join = mysqli_query($dbconnect, $jsql);
                        if ($join != "0") {
                            if (mysqli_num_rows($join)!= 0){
                                
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
$claim = $row['Claim'];
$pieces =  $units * $case;
$pieceseach = $pieces/$spaces;
$pieceseach = floor($pieceseach);
$remainder = $pieces % $spaces;
$moneypieces = number_format($pieces, 2);
$piececost = $price/$moneypieces;
$piececost = round($piececost, 2);
$piececost = number_format($piececost, 2);
$moneyspaces = number_format($spaces, 2);
$costeach = $price/$moneyspaces;
$costeach = round($costeach, 2);
$costeach = number_format($costeach, 2);
$checkpart = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId' AND `Approved` = '1' LIMIT 1";
$membyet=0;
if (mysqli_num_rows(mysqli_query($dbconnect, $checkpart)) == 1){
    $membyet =1;
}
$checkwin = "Select * FROM `{$dealcode}` WHERE `Raffwin` != '0'";
$raffwin = mysqli_num_rows(mysqli_query($dbconnect, $checkwin));
$remainder = $remainder - $raffwin;
if ($raffwin != 0){
    if (($remainder - $raffwin) == 1){
        $raffwin++;
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
                <div id="PageHeading"><h1><?php echo $point?>'s Deal</h1></div>
                <div id="ContentLeft">
                    <table border = '0'>
                        <?php 
                        while($row != '') { 
                            echo "<tr><td><h6><br /><b>UPC:</b></h6></td><td><h6><br /><a href='ItemPage.php?id=" . $itemId . "''>".$upc."</a></h6></td>";
                            echo "<tr><td><h6><br /><b>Product:</b></h6></td><td><h6><br />".$product."</h6></td>";
                            echo "<tr><td><h6><br /><b>Case Size:</b></h6></td><td><h6><br />".$case."</h6></td>";
                            echo "<tr><td><h6><br /><b>Manufacturer:</b></h6></td><td><h6><br /><a href='ManuPage.php?id=".$manuId."'>".$company."</a></h6></td>";
                            echo "<tr><td><h6><br /><b>Units:</b></h6></td><td><h6><br />".$units."</h6></td>";
                            echo "<tr><td><h6><br /><b>Price:</b></h6></td><td><h6><br />$".$price."</h6></td>";
                            echo "<tr><td><h6><br /><b>Spaces:</b></h6></td><td><h6><br />".$spaces."</h6></td>";
                            echo "<tr><td><h6><br /><b>Location:</b></h6></td><td><h6><br />".$locationd."</h6></td>";
                            echo "<tr><td><h6><br /><b>Vendor:</b></h6></td><td><h6><br />".$vendor."</h6></td>";
                            echo "<tr><td><h6><br /><b>Admin:</b></h6></td><td><h6><br /><a href = 'AdminPage.php?id=".$adminId."'>".$point."</a></h6></td>";
                            $row= '';
                        }
                        ?> 
                    </table><br />
                </div>
                <div id="ContentRight"><br />
                    <h4><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h4><br />
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
                        echo "<h4>".$remainder." Unclaimed Leftovers will be Raffled Off to Deal Participants</h4>";
                        $counteach = number_format($pieceseach,2);
                        echo "<h6><br />Count Cost = Pieces Each x Piece Cost= $".$countcost = number_format(($counteach*$piececost),2)."</h6>";
                        echo "<h6>Raffle Cost = Cost Each - Count Cost = $".$extra = number_format(($costeach-$countcost),2)."</h6>"; 
                        echo "<h6>Raffle Odds = Remaining Leftovers / Spaces = ".($remainder-$claim)."/".$spaces."</h6>";
                        echo "<h4><br /><b> ".number_format(((($remainder-$claim)/$spaces)*100),2)."% </b> Chance to get a Leftover for the cost of entering the Raffle!</h4>"; 
                    } else {
                        echo "<h4>All units distributed fairly.</h4>";
                    }
                    ?>
                    <br /><br />
                    <h4>Contact the members of the deal to</h4>
                    <h4>arrange a time to meet at the Vendor.</h4>
                    <?php 
                    $admail = "SELECT email FROM login WHERE username = '$point'";
                    $adm = mysqli_fetch_assoc(mysqli_query($dbconnect, $admail));
                    echo "<table><tr><td><h6><br/>".$point."</h6></td><td><h6><br/>Admin</h6></td><td><h6><br />".$adm['email']."</h6></td></tr>";
                        $a = 1;
                        while($row3 = mysqli_fetch_assoc($join)) { 
                            $s = $a +1;
                            $partId = $row3["UserId"];
                            $getemail = "SELECT email FROM login WHERE id = '$partId'";
                            $email = mysqli_fetch_assoc(mysqli_query($dbconnect, $getemail));
                            echo "<tr><td><h6><br/>".$row3["Username"]."</h6></td><td><h6><br/>Space ".$s."</h6></td><td><h6><br />".$email['email']."</h6></td><tr>";
                            $a++;
                        }
                        echo "</table>";?>
                    <br />
                    <?php 
                    if ($remainder == 0){
                        if($raffwin != 0) {
                            echo "<h4>See the Results of the <a href='RaffResults.php?item=".$itemId."&id=".$dealId."'>Raffle</a></h4><br />";
                        }
                        echo "<h4>When you've finished,</h4>
                        <h4>Please answer a few questions about your experience,</h4>
                        <h4>Before deactivating the Deal.</h4>
                        <br />
                        <h4><a href ='CompleteSurvey.php?item=".$itemId."&id=".$dealId."'>Completion Survey</a></h4>
                        <br />
                        <h6>The results of these Surveys will affect</h6>
                        <h6>how much Cred the Admin recieves for this Deal</h6>";
                    } else if ($membyet == 0){
                        echo "<h4>Proceed to <a href = 'Raffle.php?item=".$itemId."&id=".$dealId."'>Leftovers Raffle.</a></h6>";
                    } else if ($rafyet == 0){
                        echo "<h4>Awaiting Results of the Raffle</h4>";
                    } else {
                        echo "<h4>See the <a href = '#'>Results of the Raffle!</a></h4>";
                    }
                    ?>
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
