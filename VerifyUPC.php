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
$lib = "";
$getlib = "SELECT * FROM `upclib` WHERE `Verified` = 0";
$lib = mysqli_query($dbconnect, $getlib);
if ($lib != "") {
    if (mysqli_num_rows($lib)!= 0){
        $dbsuccess = "Unverified Products:"; 
    } else {
    $dbErr2 = "No Products to verify";
    }
} else { 
    $dbErr1 = "Couldn't Display";
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
                    <h1>UPC Verification</h1>
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
                        <td><h4>UPC</h4></td><td><h4>Product</h4></td><td><h4>Case</h4></td><td><h4>Company</h4></td><td><h4>Category</h4></td><td><h4>Verify?</h4></td><td><h4>Delete?</h4></td></thead>
                        <?php 
                        while($row = mysqli_fetch_assoc($lib)) {
                            echo "<tr><td><h6><br /><a href='ItemPage.php?id=" . $row['ID'] . "'>".$row["UPC"]."</a></h6></td>";
                            echo "<td><h6><br />".$row["ItemName"]."</h6></td>";
                            echo "<td><h6><br />".$row["CaseSize"]."</h6></td>";
                            echo "<td><h6><br />".$row["Company"]."</h6></td>";
                            echo "<td><h6><br />".$row["Category"]."</h6></td>";
                            echo "<td><h6><br /><a href='ConfirmVerify.php?id=" . $row['ID'] . "'>Confirm</h6></td>";
                            echo "<td><h6><br /><a href='RejectUPC.php?id=" . $row['ID'] . "'>Reject</h6></td></tr>";
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

