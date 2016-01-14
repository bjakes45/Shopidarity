<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
     $dbsuccess = ""; $dbErr1 =""; $dbErr2 ="";
    $valid = 0;
    $mgmt = 2;
    $getmgmt = "SELECT * FROM `mgmt` WHERE `userId` ='$userId' LIMIT 1";
    $mgmtstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getmgmt));
        if ($mgmtstatus[0]) {
            $mgmt = 1;
            $mgrcode = $mgmtstatus[2];
        } else {
            $mgmt = 0;
        }
    if ($mgmt != 1) {
        header("Location:Account.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}if (isset($_GET["id"])) {
    $itemId = strip_tags($_GET["id"]);
    $getlib = "SELECT * FROM upclib WHERE id = '$itemId' LIMIT 1";
    $lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
    if (!empty($lib)) {
        $itemname = $lib["ItemName"]; $ver = $lib["Verified"];
        $company = $lib["Company"]; $category = $lib["Category"];
        $id = $lib["ID"]; $upc = $lib["UPC"]; 
        $case = $lib["CaseSize"]; $adminId = $lib["AddedBy"];
        if ($ver == 0){
            $valid++;
        } else {
            $dbErr1 = "Already Verified.";
        }
        $checkmanu = "SELECT * FROM manufacturer WHERE Name = '$company' LIMIT 1";
        if (mysqli_num_rows(mysqli_query($dbconnect, $checkmanu)) == 1){
            $newmanu = 0;
            $manuinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $checkmanu));
            $manuId = $manuinfo["ID"];
        } else {
            $newmanu = 1;
        }
    } else {
        $dbErr1 = "No Info.";
    }
}
$admininfo = "SELECT * FROM login WHERE id = '$adminId' LIMIT 1";
$ainfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $admininfo));
$oldcred = $ainfo['TatalCred'];
$newcred = $oldcred+1;
if (isset($_POST["Confirm"])) {
    $valid++;
    if (isset($_POST["Code"])) {
        $code = strip_tags($_POST["Code"]);
        if ($code == $mgrcode) {
            $valid++;
        } else {
            $dbErr1 = "Wrong Code";
        }
        if ($valid == 3) {
            $itemcode = item.$itemId;
            $makedeal = "CREATE TABLE `{$itemcode}` (`ID` INT(8) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `Quantity` INT(8) NOT NULL, `Price` DECIMAL(8,2) NOT NULL, `Spaces` INT(8) NOT NULL, `Location` VARCHAR(20), `Vendor` VARCHAR(20),`VendId` INT(12) NOT NULL, `PointPerson` VARCHAR(20), `AdminId` INT(10) NOT NULL, `Active` TINYINT DEFAULT 1 NOT NULL, `Complete` INT(8) DEFAULT 0 NOT NULL, `Expiry` VARCHAR(20), `Claim` INT(6) NOT NULL, PRIMARY KEY(`ID`))";
            if (mysqli_query($dbconnect, $makedeal)) {
                $ratetable = rateit.$id;
                $rateit = "CREATE TABLE `{$ratetable}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `UserId` Int(10) NOT NULL, `Rating` TINYINT(2) DEFAULT '5', `Comment` VARCHAR(144), `Public` TINYINT(1) DEFAULT '1', `Delete` TINYINT(1) DEFAULT '0', PRIMARY KEY(`ID`))";
                if (mysqli_query($dbconnect, $rateit)) {
                    $firstrate = "INSERT INTO `{$ratetable}`(`Rating`, `Comment`) VALUES ('5', 'Auto Average')";
                    if (mysqli_query($dbconnect, $firstrate)){
                        $verify = "UPDATE `upclib` SET `Verified` = '1', `ManuId` = '$newId' WHERE `ID` = '$itemId'";
                        if (mysqli_query($dbconnect, $verify)){
                            $adnoticode = noti.$adminId;
                            $adminnoti = "INSERT INTO `{$adnoticode}`(`FromId`, `FromName`, `Subject`, `Message`) VALUES ('$userId', '$username', 'Item Accepted!', 'Congrats! The UPC code you submitted has been verified. You have recieved 1 Cred!')";
                            if (mysqli_query($dbconnect, $adminnoti)){
                                $credcode = cred.$adminId;
                                $givecred = "INSERT INTO `{$credcode}`(`CredIn`, `MgrId`, `Reason`) VALUES ('1', '$userId', 'UPC Validation')";
                                if (mysqli_query($dbconnect, $givecred)){
                                    $addcred = "UPDATE `login` SET `TotalCred` = '$newcred' WHERE `id` = '$adminId'";
                                    if (mysqli_query($dbconnect, $addcred)){
                                        $logcred = "INSERT INTO `bank` (`NewCred`, `UserId`, `MgrId`, `Reason`) VALUES ('1', '$adminId', '$userId', 'UPC Validation')";
                                        if (mysqli_query($dbconnect, $logcred)){
                                            if ($newmanu == 1) {
                                                $addmanu = "INSERT INTO `manufacturer`(`Name`, `AdminId`, `MgrId`) VALUES('$company','$adminId','$userId')";
                                                if (mysqli_query($dbconnect, $addmanu)) {
                                                    $newId = mysqli_insert_id($dbconnect);
                                                    $manu = manu.$newId;
                                                    $newmanu = "CREATE TABLE `{$manu}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `LibId` INT(10) NOT NULL,`UPC` BIGINT(20) NOT NULL, `ItemName` LONGTEXT, `CaseSize` INT(8) NOT NULL, `Category` VARCHAR(30), PRIMARY KEY(`ID`))";
                                                    if (mysqli_query($dbconnect, $newmanu)) {
                                                        $ratetable2 = rmanu.$newId;
                                                        $rmanu = "CREATE TABLE `{$ratetable2}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `UserId` Int(10) NOT NULL, `Rating` TINYINT(1) DEFAULT '0', `Comment` VARCHAR(144), `Public` TINYINT(1) DEFAULT '1', `Delete` TINYINT(1) DEFAULT '0', PRIMARY KEY(`ID`))";
                                                        if (mysqli_query($dbconnect, $rmanu)) {
                                                            $firstrate2 = "INSERT INTO `{$ratetable2}`(`Rating`, `Comment`) VALUES ('5', 'Auto Average')";
                                                            if (mysqli_query($dbconnect, $firstrate2)){
                                                                $manit = "INSERT INTO `{$manu}`(`LibID`, `UPC`, `ItemName`, `CaseSize`, `Category`) VALUE ('$id', '$upc', '$itemname', '$case', '$category')";
                                                                if (mysqli_query($dbconnect, $manit)){
                                                                    $dbsucess = "All done!";
                                                                } else {
                                                                    $dbErr2 = "Couldn't Add Item to Manufacturer Cupboard";
                                                                }
                                                            } else {
                                                                $dbErr2 = "Couldn't initiatie Manufacturer Ratings.";
                                                            }
                                                        } else {
                                                            $dbErr2 = "No Manufacturer Ratings";
                                                        }
                                                    } else {
                                                        $dbErr2 =  "Couldn't make Manufacturer Page";
                                                    }
                                                } else {
                                                    $dbErr2 = "Manufacturer wasn't added.";
                                                }
                                            } else {
                                                $manu = manu.$manuId;
                                                $manit = "INSERT INTO `{$manu}`(`LibID`, `UPC`, `ItemName`, `CaseSize`, `Category`) VALUE ('$id', '$upc', '$itemname', '$case', '$category')";
                                                if (mysqli_query($dbconnect, $manit)){
                                                    $dbsuccess = "The easy way";
                                                } else {
                                                    $dbErr2 = "Couldn't Add Item to Manufacturer Cupboard";                                                                   
                                                }
                                            }
                                        } else {
                                            $dbErr1 = "Bank not updated";
                                        }
                                    } else {
                                        $dbErr1 = "Cred not logged";
                                    }
                                } else {
                                    $dbErr1 = "No cred given";
                                }
                            } else {
                                $dbErr2 = "Notification not sent";
                            }
                        } else {
                            $dbErr2 = "No Verification";
                        }
                    } else {
                        $dbErr2 = "Couldn't Initiate Product Ratings";
                    }
                } else {
                    $dbErr2 = "No Product Ratings";
                    }
            } else {
                $dbErr1 = "Couldn't make Deals.";
            }
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
                <div id="PageHeading"><h1>Confirm Product</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h4><?php echo $dbErr1; echo $dbErr2; echo $dbsuccess; ?></h4><br />
                    <?php if (!isset($_POST["Add"])) { 
                        echo "<h6>Do you really want to verify Product = ".$itemname.", ID = ".$id." is real?</h6><br />
                        <h6>Made By: ".$company.".</h6>";
                        if ($newmanu == 0){
                            echo "<h6>This Manufacturer is already in the database.</h6>";
                        } else {
                            echo "<h6>A new manufacturer to our database</h6>";
                        }
                        echo "<h6><br />Posted by Admin = ".$adminId."</h6>";
                        }?><br />
                    <form method="POST" action="ConfirmVerify.php?id=<?php echo $itemId ?>">
                        <h6>Manager Code</h6><br/>
                                    <input type= "password" name= "Code" value="" /><br /><br />
                                    <input type="submit" name= "Confirm" value="Verify"><h6><br />or<a href="VerifyUPC.php">Return to UPC Verification Page</a></h6>            
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
          