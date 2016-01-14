<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $admin = 2;
    $getadmin = "SELECT `admin` FROM `login` WHERE `id` ='$userId' AND `admin` = '1' LIMIT 1";
    $adminstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getadmin));
        if ($adminstatus[0]) {
            $admin = 1;
        } else {
            $admin = 0;
        }
    if ($admin == 0) {
        header("Location:Account.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}
$getlib = "SELECT * FROM `login` WHERE `id` = '$userId'";
$lib = mysqli_fetch_assoc(mysqli_query($dbconnect, $getlib));
$tcred = $lib['TotalCred'];
$scred = $lib['SpentCred'];
$rate = $lib['AvRate'];
$adrate = $lib['AdAvRate'];
$credcode = cred.$userId;
$getcred = "SELECT * FROM `{$credcode}` WHERE `MgrId` !='0'";
?>
<!DOCTYPE html>

<html>
    <head>
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title>Shopidarity | Share & Save</title>
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
                    <h1><?php echo $username; ?>'s Admin</h1>
                </div>
                <div id="ContentLeft">
                    <h4>Cred: <b><?php echo $tcred-$scred; ?></b></h4>             
                    <h4><br />Admin Rating: <b><?php echo $adrate; ?></b></h4>             
                   </div>
                <div id="ContentRight">
                    <h2>Cred History</h2>
                    <br />
                    <h4>Cred Accumulated: <?php echo $tcred; ?></h4>
                    <h4>Cred Spent: <?php echo $scred; ?></h4>
                    <br /><h6><a href="BuyCred.php">Buy Cred</a></h6><br />
                    <table>
                        <thead><td><h4>Time</h4></td><td><h4>Cred In</h4></td><td><h4>Cred Out</h4></td><td><h4>Reason</h4></td></thead>
                    <?php
                    $cred = mysqli_query($dbconnect, $getcred);
                    while ($row = mysqli_fetch_assoc($cred)){
                        echo "<tr><td><h6>".$row['Time']."</h6></td><td><h6>".$row['CredIn']."</h6></td><td><h6>".$row['CredOut']."</h6></td><td><h6>".$row['Reason']."</h6></td></tr>";
                    }
                    ?>
                    </table>
                   </div>
            </div>
            <div id="Footer">
                <br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6>
            </div>
        </div>
    </body>
</html>


