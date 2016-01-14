<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $userloc = $_SESSION["location"];
    $usercurr = $_SESSION["currency"];
    $cup = cup.$userId;
    $admin = 2;
    $getadmin = "SELECT `admin` FROM `login` WHERE `id` ='$userId' LIMIT 1";
    $adminstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getadmin));
        if ($adminstatus[0]) {
            $admin = 1;
        } else {
            $admin = 0;
        }
    if ($admin == 0) {
        header("Location:index.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["id"])) {
    $itemId = strip_tags($_GET["id"]);
    $itemcode = item.$itemId;
    $getitem = "SELECT * FROM `upclib` WHERE `ID` = '$itemId' LIMIT 1"; 
    $item = mysqli_fetch_assoc(mysqli_query($dbconnect, $getitem));
    $upc = $item["UPC"]; $itemname = $item["ItemName"]; 
    $case = $item["CaseSize"]; $company = $item["Company"];
    $manuId = $item['ManuId']; $category = $item["Category"];
}
if (isset($_POST["Unconfvend"])) { 
    $quantity = strip_tags($_POST['Quantity']);
    $price = strip_tags($_POST['Price']);
    $spaces = strip_tags($_POST['Spaces']);
    $select = strip_tags($_POST['Country']);
    $vendor = "Unconfirmed";
}
$newvend = 0;
if (isset($_POST["NewVendor"])) { 
    $quantity = strip_tags($_POST['Quantity']);
    $price = strip_tags($_POST['Price']);
    $spaces = strip_tags($_POST['Spaces']);
    $select = strip_tags($_POST['Country']);
    if (isset($_POST['Vendor']) == true && empty($_POST['Vendor']) == false) {
        $vendor = strip_tags($_POST['Vendor']);
        $checkvendor = "SELECT * FROM vendor WHERE VendName = '$vendor'";
        if (mysqli_num_rows(mysqli_query($dbconnect, $checkvendor)) != 1){
            $addvendor = "INSERT INTO vendor(VendName, Location, AdminId) Values ('$vendor', '$userloc', '$userId')";
            if (mysqli_query($dbconnect, $addvendor)){
                 $dbsuccess = "Vendor submitted for confirmation<br/>You will be notified when it has been accepted<br />Please enter your Deal at that time.";
                $newvend = 1;
            } else {
                $dbErr1 = "Vendor couldn't be submitted.";
            }
        } else {
            $dbErr1 = "Vendor already in database. Maybe it's already awaiting Confirmation.";
        }
    }     
}
if (isset($_POST["submit"])) {
    $valid = 0;
    $quantErr1 = ""; $quantErr2 = "";
    $priceErr1 = ""; $priceErr2 = "";
    $spaceErr1 = ""; $spaceErr2 = "";
    $locationErr1 = ""; $locationErr2 = "";
    $vendorErr1 = ""; $vandorErr2 = "";
    $dbErr1 = ""; $dbsuccess = "";
    
    $cupcode = cup.$userId;
    $getcup = "SELECT * FROM `{$cupcode}` WHERE UPC = '$upc' LIMIT 1";
    if (mysqli_num_rows(mysqli_query($dbconnect, $getcup)) == 1) {
        $valid++;
    } else {
        $cupErr1 = "Product must be in your Cupboard!";
    }
    if (empty($_POST['Quantity'])) {
        $quantErr1 = "Please enter a Quantity";
    } else {
        if (isset($_POST['Quantity']) == true && empty($_POST['Quantity']) == false) {
            $quantity = strip_tags($_POST['Quantity']);
                if ($quantity > 0 && $quantity < 1000) {
                    $valid++;
                } else {
                    $quantErr2 = "Please enter a valid Quantity";
                }
        }
    }
    if (empty($_POST['Price'])) {
        $priceErr1 = "Please enter a Price";
    } else {
        if (isset($_POST['Price']) == true && empty($_POST['Price']) == false) {
            $price = strip_tags($_POST['Price']);
                if ($price > 0.01 && $price < 1000000.00) {
                    $valid++;
                } else {
                    $priceErr2 = "Please enter a valid Price";
                }
        }
    }
    if (empty($_POST['Spaces'])) {
        $spaceErr1 = "Please say how many Spaces";
    } else {
        if (isset($_POST['Spaces']) == true && empty($_POST['Spaces']) == false) {
            $spaces = strip_tags($_POST['Spaces']);
            $maxspace = $quantity * $case;    
            if ($spaces >= 1 && $spaces <= $maxspace) {
                $valid++;
            } else {
                $spaceErr2 = "Can't be more than Quantity x Case Size = ".$maxspace."";
            }
        }
    }
    if (isset($_POST['Country']) == true && empty($_POST['Country']) == false) {
            $select = strip_tags($_POST['Country']);
            $choice = strtolower($select);    
            if (preg_match("/^[a-zA-Z ]*$/",$choice)) {
                $getcity = "SELECT `City` FROM `{$choice}`";
                $dbcity = mysqli_query($dbconnect, $getcity);
                $numcity = mysqli_num_rows($dbcity);
                $valid++;
            } else {
                $locationErr2 = "Please enter a valid Country";
            }
        } else {
             $locationErr2 = "Please enter a Country";
        }    
        if (empty($_POST['Location'])) {
        $locationErr1 = "No City Info!<br/>";
        } else {
            if (isset($_POST['Location']) == true && empty($_POST['Location']) == false) {
                $location = strip_tags($_POST['Location']);
                if (preg_match("/^[a-zA-Z ]*$/",$location)) {
                    if ($location == $_SESSION['location']) {
                        $valid++;
                    } else {
                        $locationErr1 = "Only make deals where you live.";
                    }
                } else {
                    $locationErr1 = "Please enter a valid City";
                }
            }
        }
    if (empty($_POST['Vendor'])) {
        $vendorErr1 = "Please enter a Vendor";
    } else {
        if (isset($_POST['Vendor']) == true && empty($_POST['Vendor']) == false) {
            $vendor = strip_tags($_POST['Vendor']);
            $checkvend = "SELECT * FROM `vendor` WHERE `VendName` = '$vendor' AND `Confirm` = '1'";
            $vquery = mysqli_query($dbconnect, $checkvend);
            if (mysqli_num_rows($vquery) == 1) {
                $vend = mysqli_fetch_assoc($vquery);
                $vendId = $vend['ID'];
                $valid++;
            } else {
                $vendorErr2 = "Vendor not yet Confirmed";
            }
        }
    }
    if ($valid > 6){
        $makedeal = "INSERT INTO `{$itemcode}`(`Quantity`, `Price`, `Spaces`, `Location`, `Vendor`, `VendId`, `PointPerson`, `AdminId`) VALUES('$quantity','$price','$spaces','$location', '$vendor', '$vendId', '$username', '$userId')";
        if (mysqli_query($dbconnect, $makedeal)) {
            $newId = mysqli_insert_id($dbconnect);
            if ($newvend == 0){
                $vendcode = vend.$vendId;
                $venddeal = "INSERT INTO `{$vendcode}`(`ItemId`, `DealId`, `Quantity`, `Price`, `Spaces`, `Location`, `PointPerson`, `AdminId`) VALUES('$itemId','$newId','$quantity','$price','$spaces','$location','$username','$userId')";
                if (mysqli_query($dbconnect, $venddeal)){
                    $dealinfo = deal.$itemId._.$newId;
                    $joindeal = "CREATE TABLE `{$dealinfo}`(`ID` INT(5) NOT NULL AUTO_INCREMENT, `UserId` INT(10) NOT NULL, `Username` VARCHAR(30), `Location` VARCHAR(30), `PurFreq` VARCHAR(30), `Approved` TINYINT NULL, `RaffWin` TINYINT NULL, PRIMARY KEY(`ID`))";
                    if (mysqli_query($dbconnect, $joindeal)) {
                        $admincode = admin.$userId;
                        $addadmin = "INSERT INTO `{$admincode}`(ItemId, DealId) VALUES('$itemId','$newId')";
                        if (mysqli_query($dbconnect, $addadmin)) {
                            $dbsuccess = "Deal Created! <a href='ItemPage.php?id=".$itemId."'>Back to Product Page</a>";
                        } else {
                            $dbErr1 = "Admin update falied!";
                        }
                    } else {
                        $dbErr1 = "Couldn't Connect";
                    }
                } else {
                    $db1Err = "Couldn't add Deal to Vendor. <br /> Vendor is likely awaiting Confirmation.";
                }
            }
        } else {
            $dbErr1 = "No Deal!";
        }
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
        <script>
function showCity(str) {
    if (str == "") {
        document.getElementById("CityDisplay").innerHTML = "";
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
                document.getElementById("CityDisplay").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","GetCity.php?r="+str,true);
        xmlhttp.send();
    }
}
function showResult(str) {
  if (str.length==0) { 
    document.getElementById("FindVendor").innerHTML="";
    document.getElementById("FindVendor").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("FindVendor").innerHTML=xmlhttp.responseText;
      document.getElementById("FindVendor").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","FindVendor.php?q="+str,true);
  xmlhttp.send();
}
</script>
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
                    <h1><?php echo $username; echo $vendId;?>'s Admin</h1>
                </div>
                <div id="ContentLeft">
                    <table>
                    <?php
                        echo "<tr><td><h6><br /><b>UPC:</b></h6></td><td><h6><br />".$upc."</h6></td>";
                        echo "<tr><td><h6><br /><b>Product:</b></h6></td><td><h6><br />".$itemname."</h6></td>";
                        echo "<tr><td><h6><br /><b>Case Size:</b></h6></td><td><h6><br />".$case."</h6></td>";
                        echo "<tr><td><h6><br /><b>Manufacturer:</b></h6></td><td><h6><br /><a href='ManuPage.php?id=".$manuId."'>".$company."</a></h6></td>";
                        echo "<tr><td><h6><br /><b>Category:</b></h6></td><td><h6><br />".$category."</h6></td>";
                    ?>  
                    </table>
                    <br/>
                    <br/>
                    <h6>Please remember, being an Admin is a priveledge.</h6>                   
                    <h6>Misuse of this page may result in your Admin status</h6>
                    <h6>being revoked. Only add real Deals with the</h6>
                    <h6>most accurate information possible.</h6>                   
                    <h6><br />Using employee discounts in your Deals will forfeit</h6>                   
                    <h6>your Admin status. If you choose to do so, we are</h6>
                    <h6>in no way responsible for whatever action your</h6>           
                    <h6>employer may take.</h6>
                                       
                </div>
                <div id="ContentRight"><h2>Add a Deal</h2><br />
                    <form method="POST" action="NewDeal.php?id=<?php echo $itemId ?>">
                    <table border="0">
                        <tbody>
                            <tr><td><span class="error">
                                <?php if ($valid <= 5){echo "*Required Field.";}
                                echo "$dbErr1";?></span><h6><?php echo "$dbsuccess"; ?></h6></td></tr>
                            <tr>
                            <tr>
                            <td><br /><h6>Quantity</h6>
                                <input type="text" name="Quantity" value="<?php echo "$quantity";?>" />
                            </td>
                            <td><span class="error">*<?php echo "$quantErr1"; echo "$quantErr2";?></span></td>
                            </tr>
                            <tr>
                            <td><br /><h6>Price</h6>
                                <input type="text" name="Price" value="<?php echo "$price";?>" />
                            </td>
                            <td><span class="error">*<?php echo "$priceErr1"; echo "$priceErr2";?></span></td>
                            </tr>
                            <td><br /><h6>Spaces</h6>
                                <input type="text" name="Spaces" value="<?php echo "$spaces";?>" />
                            </td>
                            <td><span class="error">*<?php echo "$spaceErr1"; echo "$spaceErr2";?></span></td>
                            </tr>
                            <tr>
                                <td><br /><h6>Country</h6>
                                    <?php
                                        $getcountry = "SELECT `Country` FROM `country`";
                                        $dbcountry = mysqli_query($dbconnect, $getcountry);
                                        $numcountry = mysqli_num_rows($dbcountry);
                                        echo "<select name='Country' onchange='showCity(this.value)'>";
                                        echo "<option value=''>Select</option>";
                                        $c=0;
                                        while ($c < $numcountry) {
                                            $row = mysqli_fetch_row($dbcountry);
                                            $country = $row[0];
                                            echo "<option ";
                                            if ($select == $country){
                                                echo "selected = true ";
                                            }
                                            echo "value='".$country."'>".$country."</option>";
                                            $c++;
                                        }
                                        echo "</select>";
                                        ?>        
                                </td>
                                <td><div id='CityDisplay'></div></td>
                                <td><span class="error">*<?php echo "$locationErr1"; echo "$locationErr2";?></span></td>
                            </tr>
                            <tr>
                                <td><div id = "VendDisp"><br /><h6>Vendor</h6>
                                        <input type="text" name="Vendor" value="<?php echo "$vendor";?>" onkeyup= "showResult(this.value)"/></div>
                            <div id="FindVendor"></div></td>
                            <td><span class="error">*<?php echo "$vendorErr1"; echo "$vendorErr2";?></span></td>
                            </tr>
                            <tr>
                                <td><br />
                                    <input type="submit" name="submit" value="Make Deal">
                                </td>
                                <td><span class="error"><?php echo "$cupErr1";?></span></td>
                            </tr>
                    </table>
                    </form>
                </div>
            </div>
            <div id='Footer'><br /><br/>
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