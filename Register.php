<!--[if lt IE 7]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
    <?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
include_once 'Dbconnect.php';
if (isset($_POST["submit"])) {
    $valid = 0; 
    $emailErr1 = ""; $emailErr2 = "";
    $userErr1 = ""; $userErr2 = ""; $userErr3 = "";
    $passErr1 = ""; $passErr2 = ""; $passErr3 = "";
    $locationErr1 = ""; $locationErr2 = "";
    $fnameErr1 = ""; $fnameErr2 = "";
    $lnameErr1 = ""; $lnameErr2 = "";
    $dbErr1 = ""; $dbErr2 = ""; $dbsuccess = "";
    if (empty($_POST['Email'])) {
        $emailErr1 = "Please enter an Email address.";
    } else {
        if (isset($_POST['Email']) == true && empty($_POST['Email']) == false) {
            $email = strip_tags($_POST['Email']);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $valid++;
                } else {
                    $emailErr2 = "Please enter a valid Email.";
                }
        }
    }
    if (empty($_POST['Username'])) {
        $userErr1 = "Please enter a Username";
    } else {
        if (isset($_POST['Username']) == true && empty($_POST['Username']) == false) {
            $username = strip_tags($_POST['Username']);
                if (preg_match("/^[a-zA-Z ]*$/",$username)) {
                    $check = "SELECT * FROM login WHERE username = '$username' LIMIT 1";
                    if(mysqli_num_rows(mysqli_query($dbconnect, $check)) != 0) {
                        $userErr3 = "Username already taken.";
                    } else {
                        $valid++;
                    }
                } else {
                    $userErr2 = "Please enter a valid Username";
                }
        }
    }
    if (empty($_POST['Password'])) {
        $passErr1 = "Please enter a Password";
    } else {
        if (isset($_POST['Password']) == true && empty($_POST['Password']) == false) {
            $password = strip_tags($_POST['Password']);
                if (preg_match("/^[a-zA-Z ]*$/",$password)) {
                    if (isset($_POST['Psconfirm']) == true && empty($_POST['Psconfirm']) == false) {
                        $psconfirm = strip_tags($_POST['Psconfirm']);
                            if ($psconfirm != $password) {
                                $passErr3 = "Passwords don't match";
                            } else {
                                $spassword = sha1($password);
                                $valid++;
                            }
                    } else {
                    $passErr2 = "Please enter a valid Password";
                    }
                }
        }
    }
    if (empty($_POST['Country'])) {
    $locationErr1 = "Please enter a Country";
    } else {
        if (isset($_POST['Country']) == true && empty($_POST['Country']) == false) {
            $select = strip_tags($_POST['Country']);
            $choice = strtolower($select);    
            if (preg_match("/^[a-zA-Z ]*$/",$select)) {
                    $locationErr2 = "";//"If you can't find your city,<br /> email us to let us know your interested!";
                    $getcity = "SELECT `City` FROM `{$choice}`";
                    $dbcity = mysqli_query($dbconnect, $getcity);
                    $numcity = mysqli_num_rows($dbcity);
                    $valid++;
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
                    } else {
                        $locationErr2 = "Please enter a valid City";
                    }
            } else {
                $locationErr2 = "If you can't find your city,<br /> email us to let us know your interested!";

            }
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
    if ($valid > 6) {
        $register = "INSERT INTO login(`email`, `username`, `password`, `country`, `location`, `firstname`, `lastname`) VALUES('$email','$username','$spassword','$select','$location','$fname','$lname')";
        if (mysqli_query($dbconnect, $register)) {
            $newId = mysqli_insert_id($dbconnect);
                $noti = noti.$newId;
                $makenoti = "CREATE TABLE `{$noti}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `FromId` INT(10) NOT NULL, `FromName` VARCHAR(30), `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `Subject` LONGTEXT, `Message` LONGTEXT, `Seen` TINYINT(1) DEFAULT '0',`Delete` TINYINT(1) DEFAULT '0', PRIMARY KEY(`ID`))";
                if (mysqli_query($dbconnect, $makenoti)) {
                    $ratetable = rateus.$newId;
                    $rateus = "CREATE TABLE `{$ratetable}`(`ID` INT(10) NOT NULL AUTO_INCREMENT,`Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,`UserId` Int(10) NULL, `Rating` TINYINT(2) DEFAULT '0', `Comment` VARCHAR(144), `Public` TINYINT(1) DEFAULT '1', PRIMARY KEY(`ID`))";
                    if (mysqli_query($dbconnect, $rateus)) {
                        $firstrate = "INSERT INTO `{$ratetable}`(`Rating`, `Comment`) VALUES ('5', 'Auto Average')";
                        if (mysqli_query($dbconnect, $firstrate)){
                            $cup = cup.$newId;
                            $newcup = "CREATE TABLE `{$cup}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,`UPC` BIGINT(20) NOT NULL, `ItemName` LONGTEXT, `CaseSize` INT(8) NOT NULL, `Company` LONGTEXT, `ManuId` INT(10) NOT NULL, `Category` VARCHAR(30), `PurFreq` VARCHAR(30), `LibId` INT(10) NOT NULL, PRIMARY KEY(`ID`))";
                            if (mysqli_query($dbconnect, $newcup)) {
                                $join = join.$newId;
                                $newjoin = "CREATE TABLE `{$join}`(`ID` INT(10) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `ItemId` INT(10) NOT NULL, `DealId` INT(10) NOT NULL, `Quantity` INT(8) NOT NULL, `Price` DECIMAL(10,2) NOT NULL, `Spaces` INT(8) NOT NULL, `Location` VARCHAR(30), `Vendor` LONGTEXT, `PointPerson` VARCHAR(30), `AdminId` INT(10) NOT NULL, `Active` TINYINT DEFAULT '1' NOT NULL,`Approved` TINYINT DEFAULT '0' NOT NULL,`Complete` TINYINT DEFAULT '0' NOT NULL, `Expiry` VARCHAR(30), PRIMARY KEY(`ID`))";
                                if (mysqli_query($dbconnect, $newjoin)) {
                                    $dbsuccess ="Successfully Added! <br />Please <a href = 'Login.php'>Login</a>";
                                } else { 
                                    $dbErr1 = "Deal Center not ready.";
                                }
                            } else {
                                $dbErr1 = "Couldn't make New Cupboard.";
                            }
                        } else {
                            $dbErr1 = "Couldn't initiate Ratings";
                        }
                    } else {
                        $dbErr2 = "No Ratings.";
                    }
                } else {
                    $dbErr2 = "Notifications Problem";
                }
        } else {
            $dbErr2 = "Couldn't Register.";
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
        xmlhttp.open("GET","GetCity.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
    </head>
    <body>
        <div id="Holder">
            <div id="Header"><div style="float:left"><a href="index.php"><img src="Logo.png" height="60" width="60"></a></div>
                <div style="padding-top:13px"><h1><a href="index.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="Login.php">Login</a></li>
                        <li><a href="#">Register</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Sign up now</h1>
                </div>
                <div id="ContentLeft">
                    <h2>Make a new Account.</h2><br />
                    <h4>It's Easy and Free.</h4>
                    <h4>Get started Instantly.</h4><br />
                    <h6>Find people nearby that already</h6>
                    <h6>buy the same products as you!</h6><br />
                    <h6></h6><br />
                    
                </div>
                <div id="ContentRight">
                    <form method="POST" action="Register.php">
                    <table border="0">
                        <tbody>
                            <tr><td><span class="error">
                                <?php if ($valid <= 6){echo "*Required Field"; }
                                echo "$dbErr1"; echo $dbErr2;?></span><h4><?php echo $dbsuccess; ?></h4></td>
                            </tr>
                            <tr>    
                                <td><br /><h6>Email</h6>
                                    <input type="text" name="Email" value="<?php echo "$email";?>" />
                                </td>
                                <td><span class="error">*<?php echo "$emailErr1"; echo "$emailErr2";?></span></td>
                             </tr>
                             <tr>
                                <td><br /><h6>Username</h6>
                                    <input type="text" name="Username" value="<?php echo "$username";?>" />
                                </td>
                                <td><span class="error">*<?php echo "$userErr1"; echo "$userErr2"; echo "$userErr3";?></span></td>
                            </tr>
                            <tr>
                                <td><br /><h6>Password</h6>
                                    <input type="password" name="Password" value="<?php echo "$password";?>" />
                                </td>
                                <td><br /><h6>Confirm</h6>
                                    <input type="password" name="Psconfirm" value="<?php echo "$psconfirm";?>" />
                                </td>
                                <td><span class="error">*<?php echo "$passErr1"; echo "$passErr2"; echo "$passErr3";?></span></td>
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
                                            echo "value='".strtolower($country)."'>".$country."</option>";
                                            $c++;
                                        }
                                        echo "</select>";
                                        ?>        
                                </td>
                                <td><div id='CityDisplay'></div></td>
                                <td><span class="error">*<?php echo "$locationErr1"; echo "$locationErr2";?></span></td>
                            </tr>
                            <tr>
                                <td><br /><h6>First Name</h6>
                                    <input type="text" name="Fname" value="<?php echo "$fname";?>" />
                                </td>
                                <td><br /><h6>Last Name</h6>
                                    <input type="text" name="Lname" value="<?php echo "$lname";?>" />
                                </td>
                                <td><span class="error">*<?php echo "$fnameErr1"; echo "$fnameErr2";?>
                                    <?php echo "$lnameErr1"; echo "$lnameErr2";?></span></td>
                            </tr>
                            <tr>
                                <td><br /><h6>Date of Birth</h6>
                                    <input type="date" name="Birth" value="<?php if($birth!=""){echo $birth;} else {echo "1980-01-01";}?>" />
                                </td>
                                <td><br /><br /><h6>DOB information is important to determine</h6>
                                    <h6>eligibility for age restricted Deals.</h6>
                                </td>
                                <td><span class="error">*<?php echo "$userErr1"; echo "$userErr2"; echo "$userErr3";?></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <br /><h6>By Joining our site you agree to abide</h6>
                                    <h6>by the Terms & Conditions of our <a href="#">User Policy.</a></h6>
                                </td>
                                <td>
                                    <h6><br /><input type = 'radio' Name ='Policy' value= '1'
                                <?php print $restrict; ?>
                                >Yes&nbsp;&nbsp;&nbsp;
                            <input type = 'radio' Name ='Policy' value= '2'
                                <?php print $restict; ?>
                                >No
                                    </h6></td><td><span class='error'><br />*
                                    <?php echo $restrictErr1; ?>
                                    </span></td>
                            </tr>
                            <tr>
                                <td>
                                    <br /><input type="submit" name="submit" value="Register">
                                </td>
                                <td>
                                    <td><span class="error"><?php echo "$submit"; ;?></span></td>
                            </tr>
                        </tbody>
                    </table></form>
                </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>