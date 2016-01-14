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
    $lib = "";
}
$getlib = "SELECT * FROM upclib WHERE Verified = 1";
$lib = mysqli_query($dbconnect, $getlib);
if ($lib != "") {
    if (mysqli_num_rows($lib)!= 0){
        $dbsuccess = "Choose from any of the following:"; 
    } else {
    $dbErr2 = "Library Empty";
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
                    <h1>The UPC Library</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="#">UPC Library</a></li>
                    <li><a href="SearchLib.php">Search</a></li>
                    <li><a href="Matches.php">Matches</a></li>
                    </ul><br /><br />
                    <div style="margin-right: 35px;">
                    <?php if ($admin != 1) {
                    echo "<h6>Note: If you cannot find an item in the UPC Library,</h6>
                    <h6>Please request to become an Admin.</h6>
                    <h6>Find the link on your <a href='Account.php'>Profile</a>,</h6>
                    <h6>Answer a few questions.</h6>
                    <h6>Then help us build our Library!</h6>";
                    } else {
                        echo "<h6>Thank you for being an Admin.</h6>
                        <h6>You can now Add Items and Create Deals.</h6><br />
                        <h6>To access the Admin section of the site,</h6>
                        <h6>Find the tab that appears on your <a href='Account.php'>Profile</a>.</h6>";
                    }
                    ?></div>
                </div>
                <div id="ContentRight">
                <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2; ?></h2><br />
                <table border ="0">
                        <thead><td><h4>UPC</h4></td><td><h4>Product</h4></td><td><h4>Case</h4></td><td><h4>Company</h4></td><td><h4>Category</h4></td><td><h4>Add?</h4></td></thead>
                        <?php 
                        while($row = mysqli_fetch_assoc($lib)) {
                            echo "<tr><td><h6><br /><a href='ItemPage.php?id=" . $row["ID"] . "'>".$row["UPC"]."</a></h6></td>";
                            echo "<td><h6><br />".$row["ItemName"]."</h6></td>";
                            echo "<td><h6><br />".$row["CaseSize"]."</h6></td>";
                            echo "<td><h6><br /><a href='ManuPage.php?id=".$row["ManuId"]."'>".$row["Company"]."</a></h6></td>";
                            echo "<td><h6><br />".$row["Category"]."</h6></td>";
                            echo "<td><h6><br /><a href='AddItem.php?id=" . $row['ID'] . "'>Add?</h6></td></tr>";
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


