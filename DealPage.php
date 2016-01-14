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
    } else {
        $admin = 0;
    }
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $dbsuccess2 = ""; $dbErr3 = ""; $dbErr4 = "";
    $dbsuccess3 = ""; $dbErr5 = ""; $dbErr6 = "";
    $dbsuccess4 = ""; $joinErr = ""; 
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
                $dbsuccess = "Deal Information";
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
$moneypieces = number_format($pieces, 2);
$piececost = $price/$moneypieces;
$piececost = round($piececost, 2);
$piececost = number_format($piececost, 2);
$moneyspaces = number_format($spaces, 2);
$costeach = $price/$moneyspaces;
$costeach = round($costeach, 2);
$costeach = number_format($costeach, 2);
$checkjoin = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId'";
if(isset($_POST["Join"])) {
    if ($username != $point){
    $cupcode = cup.$userId;
    $getcup = "SELECT * FROM `{$cupcode}` WHERE `LibId` = '$itemId' Limit 1";
    if (mysqli_num_rows(mysqli_query($dbconnect, $getcup)) != 0) {
        $getlib = "SELECT * FROM login WHERE id = '$userId'";
        $lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
        $locationu = $lib["location"]; 
        $cup = mysqli_fetch_assoc(mysqli_query($dbconnect, $getcup));
        $purfreq = $cup["PurFreq"];
        $dealcode = deal.$itemId._.$dealId;       
        if ($locationd == $locationu) {           
            $joinreq = "INSERT INTO `{$dealcode}` (`UserId`, `Username`, `Location`, `PurFreq`, `Approved`) VALUES ('$userId', '$username', '$locationu', '$purfreq', '0')";
            if (mysqli_query($dbconnect, $joinreq)) {
                $dcenter = "INSERT INTO `{$joincode}` (`ItemId`, `DealId`, `Quantity`, `Price`, `Spaces`, `Location`, `Vendor`, `PointPerson`, `AdminId`) VALUES ('$itemId', '$dealId', '$units', '$price', '$spaces', '$locationd', '$vendor', '$point', '$adminId')";
            if (mysqli_query($dbconnect, $dcenter)) {
                    $dbsuccess4 = "Request to Join Deal sent!";
                } else {
                    $joinErr = "Couldn't Add to your Deal Center";
                }
            } else {
                $joinErr = "Request Failed";
            }
        } else {
            $joinErr = "Too far away!";
        }
    } else {
        $joinErr = "The Product must be in your Cupbord to Join the Deal!";
    }
} else $joinErr = "You are the Admin!";
}
$checkpart = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId' AND `Approved` != '0' LIMIT 1";
$membyet = 0 ;
if (mysqli_num_rows(mysqli_query($dbconnect, $checkpart)) == 1){
    $membyet = 1;
} else if($username == $point) {
    $membyet = 1;
}
$checkwin = "Select * FROM `{$dealcode}` WHERE `Raffwin` != '0'";
$raffwin = mysqli_num_rows(mysqli_query($dbconnect, $checkwin));
$remainder = $remainder - $raffwin;
if ($raffwin != 0){
    if (($remainder - $raffwin) == 1){
        $remainder--;
    }
}
if ($userId != $adminId){
    $checkdone = "SELECT * FROM `{$dealcode}` WHERE `UserId` = '$userId' AND `Approved` = '2'";
    if (mysqli_num_rows(mysqli_query($dbconnect, $checkdone)) == 1){
        header("Location: FinDeal.php?item=".$itemId."&id=".$dealId."");
    }
} else {
    $checkdone = "SELECT * FROM `{$dealcode}` WHERE `Approved` = '2'";
    $numdone = mysqli_num_rows(mysqli_query($dbconnect, $checkdone));
    if ($com == ($numdone + 1)){
        header("Location: FinDeal.php?item=".$itemId."&id=".$dealId."");
    }
}
$spaceleft = (($spaces - 1) - mysqli_num_rows($join))
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title></title>
        <script>
function showPrice(str, pri) {
    if (str == "") {
        document.getElementById("Display").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("Display").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","GetPrice.php?p="+str+"&q="+pri,true);
        xmlhttp.send();
    }
}
</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
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
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2><br />
                    
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
                    </table>
                    <br /><br />
                    <h4>Share this Deal:</h4>
                    <br /><div style="float: left; padding-bottom: 1px; margin-right: 5px;"><div class="fb-share-button" data-href="DealPage.php?item=<?php echo $itemId;?>&id=<?php echo $dealId;?>" data-layout="button"></div></div>
                    <div style="float: left;"><a href="https://twitter.com/share" class="twitter-share-button" data-via="shop_idarity" data-count="none">Tweet</a></div>
                    <script>!function(d,s,id){
                    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
                       if(!d.getElementById(id)){
                           js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}
                            (document, 'script', 'twitter-wjs');</script>
                        <br /><br />
                    <h6><a href='#'>Report Error</a></h6>
                </div>
                <div id="ContentRight">
                    <div style="float: left;margin-top:70px; margin-left:40px;">
                        <h4><b><?php echo $dbsuccess2; echo $dbErr3; echo $dbErr4; ?></b></h4><br />
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
                    </table></div>
                        <div style="float: left; margin-left: 105px; margin-right: 150px;">
                            <img src="dealman.jpg"></div>
                    <br /><div>
                    <?php
                    if ($remainder != 0){
                        echo "<h4>".$remainder." Unclaimed Leftovers will be Raffled Off to Deal Participants</h4>";
                        if ($membyet == 1){
                        if ($admin == 1){
                            echo "<h6><br />Admins may Spend Cred to <a href ='ClaimLeft.php?item=".$itemId."&id=".$dealId."'>Claim Leftovers</a><h6>";
                        } else {
                            echo "<h6><br />Must be an Admin with sufficient Cred to Claim Leftovers</h6>";
                        }} else {
                            echo "<h6><br />Or, participating Admins may spend Cred to Claim Leftovers</h6>";
                        }
                        $counteach = number_format($pieceseach,2);
                        echo "<h6><br />Count Cost = Pieces Each x Piece Cost= $".$countcost = number_format(($counteach*$piececost),2)."</h6>";
                        echo "<h6>Raffle Cost = Cost Each - Count Cost = $".$extra = number_format(($costeach-$countcost),2)."</h6>"; 
                        echo "<h6>Raffle Odds = Remaining Leftovers / Spaces = ".($remainder-$claim)."/".$spaces."</h6>";
                        echo "<h4><br /><b> ".number_format(((($remainder-$claim)/$spaces)*100),2)."% </b> Chance to get a Leftover for the cost of entering the Raffle!</h4>"; 
                    } else {
                        echo "<h4>All units distributed fairly.</h4>";
                    }
                    if ($spaces == 1){
                        echo "<h2><br />Only the Admin may Participate in this Deal.</h2>
                            <h4>Feel free to find this bargain on your own.</h4>";
                    } else if ((1 + mysqli_num_rows($join)) == $spaces) {
                        echo "<h2><br />".(($spaces - 1) - mysqli_num_rows($join))."/".$spaces." Spaces Remaining</h2>";
                        echo "<h4><br />Deal is Full!</h4>";
                    } else {
                        echo "<h2><br />".$spaceleft."/".$spaces." Spaces Remaining</h2>";
                        echo "<h4><br />".$dbsuccess3.$dbErr5.$dbErr6."</h4>";
                        echo "<table><tr><td><h6><br/>".$point."</h6></td><td><h6><br/>Admin</h6></td></tr>";
                        $a = 1;
                        while($row3 = mysqli_fetch_assoc($join)) { 
                            $s = $a +1;
                            echo "<tr><td><h6><br/>".$row3["Username"]."</h6></td><td><h6><br/>Space ".$s."</h6></td><tr>";
                            $a++;
                        }
                        echo "</table>";
                    }
                    ?>
                    <br/>
                    <?php echo "<form method='POST' action= 'DealPage.php?item=".$itemId."&id=".$dealId."'>";
                    if ($membyet == 0) {
                        if ($username != $point){
                            echo "<h4>How many spaces would you like to request?</h4>";
                            echo "<br /><div style='float:left;'><select name = 'SpaceReq' onchange='showPrice(this.value, ".$costeach.")'>";
                            $i = 1;
                            while ($i <= $spaceleft){
                                echo "<option value = ".$i.">".$i."</option>";
                                $i++;
                            }
                            echo "</select></div><div id = 'Display' style='float: left; margin-left: 40px;'><h4>Your cost: $".$costeach."</h4></div>";
                            echo "<br /><br /><input type ='submit' name ='Join' value='Ask to Join'/></form>";
                        } else {
                            echo "<h4></b>You are the Admin</b></h4>";
                        }
                    } else {
                        echo "<h4><b>Already a Participant.</b></h4>";
                    }
                    ?>
                    <br />
                    <?php echo "<h4>".$joinErr.$dbsuccess4."</h4>";
                    if ((($spaces - 1) - mysqli_num_rows($join)) == 0){
                        if ($membyet != 0){
                            echo "<h2><b><a href='CompleteDeal.php?item=".$itemId."&id=".$dealId."''>
                            Complete the Deal!!</a></b><h2>";
                        }
                        else if ($username == $point){
                            echo "<h2><b><a href='CompleteDeal.php?item=".$itemId."&id=".$dealId."''>
                            Complete the Deal!!</a></b><h2>";
                        }
                    }
                    ?>
                    </div>
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