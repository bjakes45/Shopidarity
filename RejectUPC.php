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
if (isset($_POST["Delete"])) {
    $valid++;
    if (isset($_POST["Code"])) {
        $code = strip_tags($_POST["Code"]);
        if ($code == $mgrcode) {
            $valid++;
        } else {
            $dbErr1 = "Wrong Code";
        }
        if ($valid == 3) {
            
        } else {
                $dbErr2 = "Couldn't make Deals.";
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
                <div id="PageHeading"><h1>Reject Product</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h4><?php echo $dbErr1; echo $dbErr2; echo $dbsuccess; ?></h4><br />
                    <?php if (!isset($_POST["Delete"])) { 
                        echo "<h6>Do you really want to reject Product = ".$itemname.", ID = ".$id."?</h6><br />
                        <h6>Made By: ".$company.".</h6>";
                        if ($newmanu == 0){
                            echo "<h6>This Manufacturer is already in the database.</h6>";
                        } else {
                            echo "<h6>A new manufacturer to our database</h6>";
                        }
                        echo "<h6><br />Posted by Admin = ".$adminId."</h6>";
                        }?><br />
                    <form method="POST" action="RejectUpc.php?id=<?php echo $itemId ?>">
                        <h6>Manager Code</h6><br/>
                                    <input type= "password" name= "Code" value="" /><br /><br />
                                    <input type="submit" name= "Delete" value="Reject"><h6><br />or<a href="VerifyUPC.php">Return to UPC Verification Page</a></h6>            
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
          
    

