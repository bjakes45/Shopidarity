<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
} else {
    header("Location:index.php");
    die();
}
if (!isset($_POST['Submit'])){
    $noti = noti.$userId;
    $checknoti = "SELECT * FROM `{$noti}` WHERE `Seen` = '0' AND `Delete` = '0'";
    $newnoti = mysqli_query($dbconnect, $checknoti);
    $numnew = mysqli_num_rows($newnoti);
    $checknoti2 = "SELECT * FROM `{$noti}` WHERE `Seen` = '1' AND `Delete` = '0'";
    $oldnoti = mysqli_query($dbconnect, $checknoti2);
    $numold = mysqli_num_rows($oldnoti);
} else {
    $noti = noti.$userId;
    $checknoti = "SELECT * FROM `{$noti}` WHERE `Seen` = '0'";
    $newnoti = mysqli_query($dbconnect, $checknoti);
    $numnew = mysqli_num_rows($newnoti);
    $checknoti2 = "SELECT * FROM `{$noti}` WHERE `Seen` = '1'";
    $oldnoti = mysqli_query($dbconnect, $checknoti2);
    $numold = mysqli_num_rows($oldnoti);
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
                    <h1><?php echo $username; ?>'s Notifications</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <?php
                        if ($numnew == 0){
                            echo "<li><a href='Notifications.php'>Notifications</a></li>";
                        } else {
                            echo "<li><a href='Notifications.php'><b>Notifications(".$numnew.")</b></a></li>";
                        }
                        ?>
                    <li><a href="ViewItem.php">Your Cupboard</a></li>
                    <li><a href="MyDeals.php">Deal Center</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <br />
                    <?php
                        if ($numnew != 0){
                            echo "<h2>New Notifications:<br/><br/></h2>";
                            echo "<table><thead><td><h4>From:</h4></td><td><h4>Subject:</h4></td><td><h4>Time Sent:</h4></td><td><h4>Delete?</h4></td>";
                            while($new = mysqli_fetch_assoc($newnoti)) {
                                echo "<tr><td><h6><br/><a href='AdminPage.php?id=" . $new["FromId"] . "'>".$new['FromName']."</a></h6></td>";
                                echo "<td><h6><br/><a href='ReadNoti.php?id=".$new['ID']."'>".$new["Subject"]."</a></h6></td>";
                                echo "<td><h6><br/>".$new["Time"]."</h6></td>";
                                echo "<td><h6><br/><a href='DeleteNoti.php?id=".$new['ID']."'>Delete?</h6></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<h2>No New Notifications!<br/><br/></h2>";
                        }
                    ?> <br /> <br />
                    <?php
                    if ($numold != 0){
                            echo "<h2>Past Notifications:<br/><br/></h2>";
                            echo "<table><thead><td><h4>From:</h4></td><td><h4>Subject:</h4></td><td><h4>Time Sent:</h4></td><td><h4>Delete?</h4></td>";
                            while($old = mysqli_fetch_assoc($oldnoti)) {
                                echo "<tr><td><h6><br/><a href='AdminPage.php?id=" . $old["FromId"] . "'>".$old['FromName']."</a></h6></td>";
                                echo "<td><h6><br/><a href='ReadNoti.php?id=".$old['ID']."'>".$old["Subject"]."</a></h6></td>";
                                echo "<td><h6><br/>".$old["Time"]."</h6></td>";
                                echo "<td><h6><br/><a href='DeleteNoti.php?id=".$old['ID']."'>Delete?</h6></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<h2>No Past Notifications!<br/><br/></h2>";
                        }
                    ?>
                    <br/>
                    <?php
                    if (!isset($_POST['Submit'])){
                    echo "<form action='Notifications.php' method='POST'>
                        <input type='Submit' name='Submit' Value='Show Deleted?'>
                        </form>";
                    } else {
                    echo "<form action='Notifications.php' method='POST'>
                        <input type='Submit' name='unSubmit' Value='Hide Deleted?'>
                        </form>";
                    }
                    ?>
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


