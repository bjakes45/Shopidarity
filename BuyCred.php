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
                        <?php if ($admin == 1){echo "<li><a href='Admin.php'>Admin</a></li>";} ?>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Buy Cred</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="10" 
                        data-amount=".10" 
                        data-currency="CAD" 
                        data-tax=".00" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>              
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="50" 
                        data-amount=".09" 
                        data-currency="CAD" 
                        data-tax=".00" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>              
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="100" 
                        data-amount=".08" 
                        data-currency="CAD" 
                        data-tax=".00" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>                            
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="500" 
                        data-amount=".07" 
                        data-currency="CAD" 
                        data-tax="0" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>              
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="1000" 
                        data-amount=".06" 
                        data-currency="CAD" 
                        data-tax="0" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="5000" 
                        data-amount=".05" 
                        data-currency="CAD" 
                        data-tax="0" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>
                    <li><script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=sell@shopi.com" 
                        data-button="buynow" 
                        data-name="Cred" 
                        data-quantity="10000" 
                        data-amount=".04" 
                        data-currency="CAD" 
                        data-tax="0" 
                        data-callback="ConfirmBuy.php" 
                        data-env="sandbox"
                    ></script></li>              
                    </ul>
                </div>
                <div id="ContentRight">
                    <br /><br /><br />
                    <h6>Spend <b>$1.00</b> to buy <b>10 Cred</b> at <b>$0.10</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$4.50</b> to buy <b>50 Cred</b> at <b>$0.09</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$8.00</b> to buy <b>100 Cred</b> at <b>$0.08</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$35.00</b> to buy <b>500 Cred</b> at <b>$0.07</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$60.00</b> to buy <b>1000 Cred</b> at <b>$0.06</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$250.00</b> to buy <b>5000 Cred</b> at <b>$0.05</b> each</h6><br />
                    <br /><br /><h6><br />Spend <b>$400.00</b> to buy <b>10000 Cred</b> at <b>$0.04</b> each</h6><br />
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


