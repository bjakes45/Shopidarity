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
    if ($admin == 1) {
        header("Location:Account.php");
        die();
    }
    $valid = 0;
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $a1Err = ""; $a2Err = ""; $a3Err = "";
    $ch1_1 = 'unchecked'; $a1_1 = 0;
    $ch1_2 = 'unchecked'; $a1_2 = 0;
    $ch1_3 = 'unchecked'; $a1_3 = 0;
    $ch2_1 = 'unchecked'; $a2_1 = 0;
    $ch2_2 = 'unchecked'; $a2_2 = 0;
    $ch2_3 = 'unchecked'; $a2_3 = 0;
    $ch2_4 = 'unchecked'; $a2_4 = 0;
    $ch2_5 = 'unchecked'; $a2_5 = 0;
    $ch2_6 = 'unchecked'; $a2_6 = 0;
    $ch3_1 = 'unchecked'; $a3_1 = 0;
    if (isset($_POST['Submit'])) {
        if (isset($_POST['ch1_1'])) {
            $a1_1 = $_POST['ch1_1'];
            if ($a1_1=='1') {
                $ch1_1 = 'checked';
            }
        }
        if (isset($_POST['ch1_2'])) {
            $a1_2 = $_POST['ch1_2'];
            if ($a1_2 == '2') {
                $ch1_2 = 'checked';
            }
        }
        if (isset($_POST['ch1_3'])) {
            $a1_3 = $_POST['ch1_3'];
            if ($a1_3 == '3') {
                $ch1_3 = 'checked';
            }
        }
        if (isset($_POST['ch2_1'])) {
            $a2_1 = $_POST['ch2_1'];
            if ($a2_1=='1') {
                $ch2_1 = 'checked';
            }
        }
        if (isset($_POST['ch2_2'])) {
            $a2_2 = $_POST['ch2_2'];
            if ($a2_2 == '2') {
                $ch2_2 = 'checked';
            }
        }
        if (isset($_POST['ch2_3'])) {
            $a2_3 = $_POST['ch2_3'];
            if ($a2_3 == '3') {
                $ch2_3 = 'checked';
            }
        }
        if (isset($_POST['ch2_4'])) {
            $a2_4 = $_POST['ch2_4'];
            if ($a2_4=='4') {
                $ch2_4 = 'checked';
            }
        }
        if (isset($_POST['ch2_5'])) {
            $a2_5 = $_POST['ch2_5'];
            if ($a2_5 == '5') {
                $ch2_5 = 'checked';
            }
        }
        if (isset($_POST['ch2_6'])) {
            $a2_6 = $_POST['ch2_6'];
            if ($a2_6 == '6') {
                $ch2_6 = 'checked';
            }
        }
        if (isset($_POST['ch3_1'])) {
            $a3_1 = $_POST['ch3_1'];
            if ($a3_1=='1') {
                $ch3_1 = 'checked';
            } else if ($a3_1=='2') {
                $ch3_2 = 'checked';
            }   
        }
        $a1 = $a1_1.$a1_2.$a1_3;
        if ($a1 != '000') {
            $valid++;
        } else {
            $a1Err = "Not Answered";
        }
        $a2 = $a2_1.$a2_2.$a2_3.$a2_4.$a2_5.$a2_6;
        if ($a2 != '000000') {
            $valid++;
        } else {
            $a2Err = "Not Answered";
        }
        if ($a3_1 == '1' || $a3_1 == '2' ) {
            $valid++;
        } else {
            $a3Err = "Not Answered";
        }
        if ($valid == 3) {
            $query = "SELECT userId FROM reqadmin WHERE userId = '$userId' LIMIT 1";
            $rereq = mysqli_query($dbconnect, $query);
            if(empty(mysqli_fetch_assoc($rereq))){
                $sql = "INSERT INTO reqadmin(userId, a1, a2, a3) VALUES('$userId','$a1', $a2, $a3_1)";
                if(mysqli_query($dbconnect, $sql)){
                    $dbsuccess = "Admin Request Sent";
                } else {
                    $dbErr1 = "Couldn't Send Request";
                }
            } else {
                $dbErr1 = "Request already pending";
            }
        }
    }
} else {
    header("Location:index.php");
    die();
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
                    <h1>Welcome <?php echo $username; ?></h1>
                </div>
                <div id="ContentLeft"><h6>To request becoming an Admin,</h6>
                    <h6>please completely fill out the following:</h6>
                    </div>
                <div id="ContentRight">
                    <form method ="POST" action="RequestAdmin.php">
                        <table>
                            <tr><td><span class="error">
                                <?php if ($valid < 3){echo "*Required Field.";}
                                echo "$dbErr1";?></span><h6><?php echo "$dbsuccess";?></h6></td></tr>
                            <tr>    
                                <td><h6><br />What interests you about becoming an Admin?</h6>
                            <h6><br /><input type = 'checkbox' Name ='ch1_1' value ="1" 
                                <?php print $ch1_1; ?>
                                >Adding new UPC's 
                            <input type = 'checkbox' Name ='ch1_2' value="2" 
                                <?php print $ch1_2; ?>
                                >Making Deals<br /><br />
                            <input type = 'checkbox' Name ='ch1_3' value="3" 
                                <?php print $ch1_3; ?>
                                >Collecting Cred
                            <input type = 'checkbox' Name ='ch1_4' value="4" 
                                <?php print $ch1_4; ?>
                                >Organizing the Marketplace
                            </h6></td><td><span class='error'>*
                                <?php echo $a1Err; ?></span></td></tr>
                            <tr>
                                <td><h6><br /><br />How do you currently use Shopidarity?</h6>
                            <h6><br /><input type = 'checkbox' Name ='ch2_1' value ="1" 
                                <?php print $ch2_1; ?>
                                >Browsing UPC's 
                            <input type = 'checkbox' Name ='ch2_2' value="2" 
                                <?php print $ch2_2; ?>
                                >Managing Cupboard
                            <input type = 'checkbox' Name ='ch2_3' value="3" 
                                <?php print $ch2_3; ?>
                                >Finding and participating in Deals<br /><br />
                            <input type = 'checkbox' Name ='ch2_4' value="4" 
                                <?php print $ch2_4; ?>
                                   >Exploring Matches
                            <input type = 'checkbox' Name ='ch2_5' value="5" 
                                <?php print $ch2_5; ?>
                                >Rating things on the site
                            <input type = 'checkbox' Name ='ch2_6' value="6" 
                                <?php print $ch2_6; ?>
                                >Reporting bad Information
                            </h6></td><td><span class='error'>*
                                <?php echo $a2Err; ?>
                                </span></td></tr>
                            <tr>
                                <td><h6><br /><br />Have you referred any friends to Shopidarity?</h6>
                            <h6><br /><input type = 'radio' Name ='ch3_1' value= '1'
                                <?php print $ch3_1; ?>
                                >Yes
                            <input type = 'radio' Name ='ch3_1' value= '2'
                                <?php print $ch3_2; ?>
                                >No
                                </h6></td><td><span class='error'>*
                                    <?php echo $a3Err; ?>
                                    </span></td></tr>
                            <tr>
                            <td><br /><input type='submit' name='Submit' value='Submit'>
                            </td><td></td></tr>
                        </table>
                    </form>
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


