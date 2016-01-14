<?php

error_reporting(E_ALL & ~E_NOTICE);
session_start();
$valid = 0;
if (isset($_POST["submit"])) {
    include_once('Dbconnect.php');
    if (empty($_POST['Username'])) {
        $userErr1 = "Please enter a Username";
    } else {
        if (isset($_POST['Username']) == true && empty($_POST['Username']) == false) {
            $username = strip_tags($_POST['Username']);
            if (preg_match("/^[a-zA-Z ]*$/",$username)) {
                $valid++;
            } else {
                $userErr1 = "Please enter a valid username";
            }
        }
    }
    if (empty($_POST['Password'])) {
        $passErr1 = "Please enter a Password";
    } else {
        if (isset($_POST['Password']) == true && empty($_POST['Password']) == false) {
            $password = strip_tags($_POST['Password']);
            if (preg_match("/^[a-zA-Z ]*$/",$password)) {
                $password = sha1($password);
                $valid++;
            } else {
                $passErr1 = "Please enter a valid username";
            }
        }
    }
    if ($valid == 2){
        $sql = "SELECT id,username,password,country,location FROM login WHERE username = '$username' LIMIT 1";
        $query = mysqli_query($dbconnect, $sql);
    }
    if ($query) {
        $row = mysqli_fetch_row($query);
        $userid = $row[0];
        $dbusername = $row[1];
        $dbpassword = $row[2];
        $dbcountry = $row[3];
        $dblocation = $row[4];
    } else {
        $userErr1 = "Username not found";
    }
    if ($username == $dbusername && $password == $dbpassword) {
        $getcurrency = "SELECT `Currency` FROM `country` WHERE `Country` = '$dbcountry'";
        $currency = mysqli_query($dbconnect, $getcurrency);
        if ($currency) {
            $row2 = mysqli_fetch_row($currency);
            $dbcurrency = $row2[0];
            $_SESSION['username'] = $dbusername;
            $_SESSION['id'] = $userid;
            $_SESSION['country'] = $dbcountry;
            $_SESSION['location'] = $dblocation;
            $_SESSION['currency'] = $dbcurrency;
            header('Location: Account.php');
        } else {
            $userErr1 = "No currency info.";
        }
    } else {
        $wrong = "Incorrect Username or Password.";
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
            <div id="Header"><div style="float:left"><a href="index.php"><img src="Logo.png" height="60" width="60"></a></div><div style="padding-top:13px"><h1><a href="index.php"><b>HOPIDARITY</b></a></h1></div></div>
            <div id="NavBar">
                <nav>
                    <ul>
                        <li><a href="#">Login</a></li>
                        <li><a href="Register.php">Register</a></li>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Enter your information</h1>
                </div>
                <div id="ContentLeft">
                    <h2>Welcome back!</h2>
                    <br />
                    <h6>Having Problems?</h6>
                    <h6><a href='ForgotPassword.php'>Password Retrieval</a></h6>
                </div>
                <div id="ContentRight"><br />
                    <form method="POST" action="Login.php">
                    <table borders="0">
                        <tbody>
                            <tr>
                                <td><br /><h6>Username</h6>
                                    <input type="text" name="Username" value="" />
                                </td>
                                <td>
                                    <span class="error"><?php echo $userErr1; ?></span> 
                                </td>
                            </tr>
                            <tr>
                                <td><br /><h6>Password</h6>
                                    <input type="password" name="Password" value="" />
                                </td>
                                <td>
                                    <span class="error"><?php echo $passErr1 ?></span> 
                                </td>
                            </tr>
                            <tr>
                                <td><input type="submit" name="submit" value="Log In" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
            <div id="Footer"><br /><br/><br />
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>
