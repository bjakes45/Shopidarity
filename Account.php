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
        if ($adminstatus[0] == 1) {
            $admin = 1;
        } else if($adminstatus[0] == 2){
            $admin = 2;
        } else {
            $admin = 0;
        }
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $info = "";
    $mgmt = 2;
    $getmgmt = "SELECT `userId` FROM `mgmt` WHERE `userId` ='$userId' LIMIT 1";
    $mgmtstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getmgmt));
        if ($mgmtstatus[0]) {
            $mgmt = 1;
        } else {
            $mgmt = 0;
        }
} else {
    header("Location:index.php");
    die();
}
$getinfo = "SELECT * FROM login WHERE id = '$userId' LIMIT 1";
$info = mysqli_query($dbconnect, $getinfo);
if ($info != "") {
    if (mysqli_num_rows($info)!= 0){
        $dbsuccess = "Your Information"; 
    } else {
    $dbErr2 = "Info Empty?";
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
            <div id="Header"><div style="float:left"><a href="#"><img src="Logo.png" height="60" width="60"></a></div>
                <div style="padding-top:13px"><h1><a href="#"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="Account.php">Profile</a></li>
                        <li><a href="Cupboard.php">Cupboard</a></li>
                        <li><a href="Library.php">Library</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                        <?php if ($admin == 1){echo "<li><a href='Admin.php'>Admin</a></li>";}
                                else if($admin == 2){echo "<li><a href='Mod.php'>Mod</a></li>";}?>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading" style="height: 40px;">
                    <div style="float: left;"><h1>Welcome <?php echo $username; ?></h1></div>
                    <div style="float: right; margin-right: 11px;"><a class="coinbase-button" data-code="21c9111a4022ad9299a88b7cb2bb05cb" data-button-style="donation_small" href="https://www.coinbase.com/checkouts/21c9111a4022ad9299a88b7cb2bb05cb">Donate Bitcoins</a><script src="https://www.coinbase.com/assets/button.js" type="text/javascript"></script></div>
                </div>
                <div id="ContentLeft">
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2>
                    <h6><br /><a href='EditProfile.php'>Edit?</a></h6><br />
                    <table>
                        <?php 
                        while($row = mysqli_fetch_assoc($info)) {
                            echo "<tr><td><h6><br /><b>Email:</b></h6></td><td><h6><br />".$row["email"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Username:</b></h6></td><td><h6><br />".$row["username"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Country:</b></h6></td><td><h6><br />".$_SESSION["country"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Location:</b></h6></td><td><h6><br />".$row["location"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Currency:</b></h6></td><td><h6><br />".$_SESSION["currency"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>First Name:</b></h6></td><td><h6><br />".$row["firstname"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Last Name:</b></h6></td><td><h6><br />".$row["lastname"]."</h6></td>";
                            
                        }
                        ?>
                    </table>
                    <br />
                    <?php if ($admin == 0){
                        echo "<h6>Or <a href='RequestAdmin.php'>Request to become an Admin.</a></h6><br />
                        <h6>Then you will be able to add New Items to the Library</h6>
                        <h6>and Create Deals!</h6>";
                    } else if ($admin == 2){
                        echo "<h6>You have been certified as a Company Moderator</h6><br />
                        <h6>Your Account is ineligible to become an Admin</h6>
                        <h6>Manage your Company's page from the <a href='#'>Mod Section</a></h6>";
                    } else {
                        echo "<h6>You are already an Admin,</h6>
                        <h6>Feel free to Add Items to the Library,</h6>
                        <h6>Or Create New Deals.</h6><br />";
                    }
                    if ($mgmt == 1) { echo "<h6><br /><a href = 'Manager.php'>Manage Website</a></h6>";}
                    ?>
                </div>
                <div id="ContentRight">
                    <div style="float: left; margin: 10px;"><img src="cashgal.png" height="350" width="350"></div>
                    <div style="text-align: center; float: left; margin-top: 100px;">
                    <h6>Get started by adding Products to your <a href="Cupboard.php">Cupboard.</a></h6>
                    <br /><h6>Search the <a href='SearchLib.php'>UPC Library</a> to find your favorites.</h6>
                    <br /><h6>Also, join Deals from the link on each Product's Page.</h6><br />
                    
                    </div>
                </div>
            </div>
            <div id="Footer">
                <br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else if($admin == 0){echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6>
            </div>
        </div>
    </body>
</html>
