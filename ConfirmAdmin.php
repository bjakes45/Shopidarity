<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $selfname = $_SESSION["username"];
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
    $safeId = strip_tags($_GET["id"]);
    $query = "SELECT * FROM login WHERE id = '$safeId' LIMIT 1";
    $getadmin = mysqli_fetch_assoc(mysqli_query($dbconnect, $query));
    if (!empty($getadmin)) {
        $id = $getadmin['id']; $username = $getadmin['username'];
        $admin = $getadmin['admin']; $joined = $getadmin['joined'];
        if ($admin == 0) {
            $valid++;
        } else {
            $dbErr1 = "No info."; 
        }
    }
    if (isset($_POST["Confirm"])) {
        $valid++;
        if (isset($_POST["Code"])) {
            $code = strip_tags($_POST["Code"]);
            if ($code == $mgrcode) {
                $valid++;
            } else {
                $dbErr1 = "Wrong Code";
            }
        }
            if ($valid == 3) {
                $admintable = admin.$id;
                $maketable = "CREATE TABLE `{$admintable}`(`ID` INT(8) NOT NULL AUTO_INCREMENT, `ItemId` INT(10) NOT NULL, `DealId` INT(10) NOT NULL, `Created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `Active` TINYINT DEFAULT '1' NOT NULL, PRIMARY KEY(`ID`))";
                if (mysqli_query($dbconnect, $maketable)) {
                    $credcode = cred.$id;
                    $makecred = "CREATE TABLE `{$credcode}`(`ID` INT(10) NOT NULL AUTO_INCREMENT,`Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `CredIn` INT(12) NOT NULL, `CredOut` INT(12)NOT NULL, `MgrId` INT(12) NOT NULL, `Reason` VARCHAR(30), PRIMARY KEY(`ID`))";
                    if (mysqli_query($dbconnect, $makecred)){
                        $usnoticode = noti.$id;
                        $usernoti = "INSERT INTO `{$usnoticode}`(`FromId`, `FromName`, `Subject`, `Message`) VALUES ('$userId', '$selfname', 'Admin Granted!', 'Thank you! Your Admin Request has been accepted. Enjoy exploring the features you have access to!')";
                        if (mysqli_query($dbconnect, $usernoti)){
                            $ratetable = ratead.$id;
                            $ratead = "CREATE TABLE `{$ratetable}`(`ID` INT(10) NOT NULL AUTO_INCREMENT,`Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `UserId` Int(10) NOT NULL, `Rating` TINYINT(1) DEFAULT '0', `Comment` VARCHAR(144), `Public` TINYINT(1) DEFAULT '1', PRIMARY KEY(`ID`))";
                            if (mysqli_query($dbconnect, $ratead)) {
                                $firstrate = "INSERT INTO `{$ratetable}`(`Rating`, `Comment`) VALUES ('5', 'Auto Average')";
                                if (mysqli_query($dbconnect, $firstrate)){
                                    $delreq = "UPDATE `reqadmin` SET `delete` = 1 WHERE `userId` = '$id'";
                                    if (mysqli_query($dbconnect, $delreq)) {
                                        $makeadmin = "UPDATE `login` SET `admin` = 1 WHERE `id` = '$id'";
                                        if (mysqli_query($dbconnect, $makeadmin)){
                                            $dbsuccess = "It is done!";
                                        } else {
                                            $dbErr2 = "WTF?";
                                        }
                                    } else {
                                        $dbErr2 = "So close!";
                                    }
                                }
                            } else {
                                $dbErr2 = "No ratings";
                            }
                        } else {
                            $dbErr2 = "Couldn't Notify User.";
                        }
                    } else { 
                        $dbErr2 = "No Cred Account Made";
                    }
                } else {
                    $dbErr2 = "Table didn't work!";
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
                <div id="PageHeading"><h1>Confirm Admin</h1></div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h4><?php echo $dbErr1; echo $dbErr2; echo $dbsuccess; echo $upcErr1; ?></h4><br />
                    <?php if (!isset($_POST["Add"])) { 
                        echo "<h6>Do you really want to make User = ".$username.", ID = ".$id." an Admin?</h6><br />
                        <h6>They have been a member since: ".$joined."</h6>";  
                    }?><br />
                    <form method="POST" action="ConfirmAdmin.php?id=<?php echo $safeId ?>">
                        <h6>Manager Code</h6><br/>
                                    <input type= "password" name= "Code" value="" /><br /><br />
                        <input type="submit" name= "Confirm" value="Make Admin"><h6>or<a href="AdminRequests.php">Return to Admin Requests Page</a></h6>            
                    </form>
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

