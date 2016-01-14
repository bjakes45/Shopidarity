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
                $dbsuccess = "Leftovers Raffle";
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
$claim= $row['Claim'];
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
$checkpart = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId' AND `Approved` != '0' LIMIT 1";
$membyet=0;
if (mysqli_num_rows(mysqli_query($dbconnect, $checkpart)) == 1){
    $membyet =1;
}
$checkwin = "SELECT * FROM `{$dealcode}` WHERE RaffWin != 0";
$raffwin = mysqli_num_rows(mysqli_query($dbconnect, $checkwin));
$rafwin= "";
if (isset($_POST['Raffle'])){
    $checkwin = "SELECT * FROM `{$dealcode}` WHERE RaffWin != 0";
    if ((mysqli_num_rows(mysqli_query($dbconnect, $checkwin))) == 0){    
        while ($remainder > 0){
            $winner = rand(1, $spaces);
            if ($rafwin[$winner] != 1){
                $rafwin[$winner] = 1;
                if ($winner != 1){
                    $wincode = $winner-1;
                    $givewin = mysqli_query($dbconnect, "UPDATE `{$dealcode}` SET `RaffWin` = 1 WHERE ID = '$wincode'");
                } else {
                    $givewin = mysqli_query($dbconnect, "UPDATE `{$dealcode}` SET `RaffWin` = 2 WHERE ID = '1'");
                }
                $raffwin++;
            }
        }
    } else {
        $raffErr = "Only Raffle once!";
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
                    <br /><div id="RafResults">
                    <table><thead><td><h4>Participant</h4></td><td><h4>Win?</h4></td></thead>
                        <tr><td><h6><br/><?php echo $point; ?></h6></td><td><h6><br />
                        <?php 
                        $chicken = "Select * FROM `{$dealcode}` WHERE `ID` = '1' ";
                        $dinner = mysqli_fetch_assoc(mysqli_query($dbconnect, $chicken));
                        if ($dinner['RaffWin'] == 2){
                            echo "Winner!";
                            if ($raffwin != 0){
                                if (($remainder - $raffwin) == 1){
                                    $s2  = "Winner!";
                                } else {
                                    $s2 = "Sorry";
                                }
                            }
                        } else if ($dinner['RaffWin'] == 1){
                            if ($raffwin != 0){
                                if (($remainder - $raffwin) == 1){
                                    echo "Winner!";
                                    $s2 = "Winner!";
                                    $raffwin++;
                                } else {
                                    echo "Sorry";
                                    $s2 = "Winner!";
                                }  
                            }
                        } else {
                            echo "Sorry";
                            $s2 = "Sorry";
                        }
                        echo "</h6></td></tr>";
                        echo "<tr><td><h6>".$dinner['Username']."</h6></td><td><h6>".$s2."</h6></td></tr>";
                        $a = 2;
                        while ($a < ($spaces)) {
                            $chicken = "Select * FROM `{$dealcode}` WHERE `ID` = '$a' ";
                            $dinner = mysqli_fetch_assoc(mysqli_query($dbconnect, $chicken));
                            echo "<tr><td><h6>".$dinner['Username']."</h6></td><td><h6>";
                            if ($dinner['RaffWin'] == 1){
                                echo "Winner!";
                            } else {
                                echo "Sorry";
                            }
                            echo "</h6></td></tr>";
                            $a++;
                        }
                        ?>
                        </table>
                        </div>
                    <div><br />
                        <h4>Raffle off <?php echo $remainder ?> Leftover pieces?</h4>
                        <h4><br /><?php echo number_format(((($remainder-$claim)/$spaces)*100),2); ?>% chance each!</h4></div>
                    <br />
                    <?php 
                    if (!isset($_POST['Raffle'])){
                    echo "<form method='POST' action ='Raffle.php?item=".$itemId."&id=".$dealId."'>
                        <input type='submit' name='Raffle' value='Raffle!' />
                    </form>";
                    } else {
                        echo "<h4><a href='RaffResults.php?item=".$itemId."&id=".$dealId."'>Raffle Results Page</a></h4>";
                    }
                    echo "<h4><br />".$raffErr."</h4>";
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


