<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $mgmt = 2;
    $getmgmt = "SELECT `userId` FROM `mgmt` WHERE `userId` ='$userId' LIMIT 1";
    $mgmtstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getmgmt));
        if ($mgmtstatus[0]) {
            $mgmt = 1;
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
}
$unconfvendor = "SELECT * FROM vendor WHERE Confirm = '0'";
$vquery = mysqli_query($dbconnect, $unconfvendor);
if (mysqli_num_rows($vquery)!= 0){
    $dbsuccess = "Unconfirmed Vendors:";
} else {
    $dbErr1 = "No Vendors Unconfirmed";
} 
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title>SHOPIDARITY | Share & Save</title>
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
                    <h1>Vendor Confirmation</h1>
                    </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="AdminRequests.php">Admin Requests</a></li>              
                    <li><a href="VerifyUPC.php">UPC Verification</a></li>              
                    <li><a href="ConfirmVendor.php">Confirm Vendors</a></li>                            
                    <li><a href="CredBank.php">Cred Bank</a></li>              
                    <li><a href="#">Company Certification</a></li>
                    <li><a href="#">Error Reports</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2; ?></h2><br />
                <table border ="0">
                    <thead>
                        <td><h4>Vendor</h4></td><td><h4>Location</h4></td><td><h4>AdminId</h4></td><td><h4>Verify?</h4></td><td><h4>Delete?</h4></td></thead>
                        <?php 
                        while($row = mysqli_fetch_assoc($vquery)) {
                            echo "<tr><td><h6><br />".$row["VendName"]."</a></h6></td>";
                            echo "<td><h6><br />".$row["Location"]."</h6></td>";
                            echo "<td><h6><br />".$row["AdminId"]."</h6></td>";
                            echo "<td><h6><br /><a href='ConfVend.php?id=" . $row['ID'] . "'>Confirm</h6></td>";
                            echo "<td><h6><br /><a href='#'>Reject</h6></td></tr>";
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

