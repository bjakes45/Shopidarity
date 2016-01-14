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
if (isset($_POST["submit"])) {
    $valid = 0;
    $locationErr1 = ""; $locationErr2 = "";
    $fnameErr1 = ""; $fnameErr2 = "";
    $lnameErr1 = ""; $lnameErr2 = "";
    $dbErr1 = ""; $dbsuccess = "";
    if (empty($_POST['Currency'])) {
        $currErr1 = "Please enter a Currency";
        } else {
            if (isset($_POST['Currency']) == true && empty($_POST['Currency']) == false) {
                $curr = strip_tags($_POST['Currency']);
                if ($curr == 1) {
                    $valid++;
                } else {
                    $currErr2 = "Coming Soon";
                }
            }
        }
    if (isset($_POST['Country']) == true && empty($_POST['Country']) == false) {
            $select = strip_tags($_POST['Country']);
            $choice = strtolower($select);    
            if (preg_match("/^[a-zA-Z ]*$/",$choice)) {
                    $getcity = "SELECT `City` FROM `{$choice}`";
                    $dbcity = mysqli_query($dbconnect, $getcity);
                    $numcity = mysqli_num_rows($dbcity);
                    $valid++;
                    $_SESSION['country'] = $select;
                    $newcurrency = "SELECT `Currency` FROM `country` WHERE `Country` = '$choice'";
                    $newcurr = mysqli_fetch_assoc(mysqli_query($dbconnect, $newcurrency));
                    $_SESSION['currency'] = $newcurr['Currency'];
                } else {
                    $locationErr2 = "Please enter a valid Country";
                }
        }    
        if (empty($_POST['Location'])) {
        $locationErr1 = "Please enter a City";
        } else {
            if (isset($_POST['Location']) == true && empty($_POST['Location']) == false) {
                $location = strip_tags($_POST['Location']);
                if (preg_match("/^[a-zA-Z ]*$/",$location)) {
                        $valid++;
                        $_SESSION['location'] = $location;
                    } else {
                        $locationErr2 = "Please enter a valid City";
                    }
            } else {
                $locationErr2 = "If you can't find your city,<br /> email us to let us know your interested!";

            }
        }
    if (empty($_POST['Fname'])) {
        $fnameErr1 = "Please enter a First Name";
    } else {
        if (isset($_POST['Fname']) == true && empty($_POST['Fname']) == false) {
            $fname = strip_tags($_POST['Fname']);
                if (preg_match("/^[a-zA-Z ]*$/",$fname)) {
                    $valid++;
                } else {
                    $fnameErr2 = "Please enter a valid First Name";
                }
        }
    }
    if (empty($_POST['Lname'])) {
        $lnameErr1 = "Please enter a Last Name";
    } else {
        if (isset($_POST['Lname']) == true && empty($_POST['Lname']) == false) {
            $lname = strip_tags($_POST['Lname']);
                if (preg_match("/^[a-zA-Z ]*$/",$lname)) {
                    $valid++;
                } else {
                    $lnameErr2 = "Please enter a valid Last Name";
                }
        }
    }
    if ($valid > 4) {
        $edit = "UPDATE login SET country = '$select', location = '$location', firstname = '$fname', lastname = '$lname' WHERE id = '$userId'";
        if (mysqli_query($dbconnect, $edit)) {
            $dbsuccess = "Account Edited.<a href='Account.php'>Return to Profile.</a>";
        } else {
            $dbErr1 = "Please try again";
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
        <script>
function showCity(str) {
    if (str == "") {
        document.getElementById("CityDisplay").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("CityDisplay").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","GetCity.php?p="+str,true);
        xmlhttp.send();
    }
}
</script>
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
                <div id="ContentLeft">
                    <h2></h2>
                </div>
                <div id="ContentRight">
                    <h2>Edit Profile</h2><br />
                    <form method="POST" action="EditProfile.php">
                        <table border="0">
                            <tbody>
                                <tr>
                                    <td><span class="error">
                                    <?php echo "$dbErr1";?></span><h6>
                                    <?php echo "$dbsuccess";?></h6></td><td></td>
                                </tr>
                                <tr>
                                    <td><br /><h6>Currency</h6>
                                        <select name= "Currency">
                                            <option value = 1>National</option>
                                            <option value = 2>BitCoin</option>
                                        </select>
                                    </td><td><span class="error">*
                                        <?php echo "$currErr1"; echo "$currErr2";?><br />
                                    </span></td>
                            </tr>
                                <tr>
                                <td><br /><h6>Country</h6>
                                    <?php
                                        $getcountry = "SELECT `Country` FROM `country`";
                                        $dbcountry = mysqli_query($dbconnect, $getcountry);
                                        $numcountry = mysqli_num_rows($dbcountry);
                                        echo "<select name='Country' onchange='showCity(this.value)'>";
                                        echo "<option value=''>Select</option>";
                                        $c=0;
                                        while ($c < $numcountry) {
                                            $row = mysqli_fetch_row($dbcountry);
                                            $country = $row[0];
                                            echo "<option ";
                                            if ($select == $country){
                                                echo "selected = true ";
                                            }
                                            echo "value='".$country."'>".$country."</option>";
                                            $c++;
                                        }
                                        echo "</select>";
                                        ?>        
                                </td>
                                <td><div id='CityDisplay'></div></td>
                                <td><span class="error">*<?php echo "$locationErr1"; echo "$locationErr2";?></span></td>
                            </tr>
                                <tr>
                                <td><h6><br />First Name</h6>
                                    <input type="text" name="Fname" value="<?php echo "$fname";?>" />
                                </td>
                                <td><h6><br />Last Name</h6>
                                    <input type="text" name="Lname" value="<?php echo "$lname";?>" />
                                </td>
                                <td><span class="error">*<?php echo "$fnameErr1"; echo "$fnameErr2";?><br />
                                    <?php echo "$lnameErr1"; echo "$lnameErr2";?></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="submit" value="Edit">
                                </td>
                            </tr>
                            </tbody>
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
