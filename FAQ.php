<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $login = 1;
} else {
    $login = 0;
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
                    <?php if ($login == 0){
                    echo "<ul>
                        <li><a href='Login.php'>Login</a></li>
                        <li><a href='Register.php'>Register</a></li>
                    </ul>";
                    } else {
                        echo "<ul>
                        <li><a href='Account.php'>Profile</a></li>
                        <li><a href='Cupboard.php'>Cupboard</a></li>
                        <li><a href='Library.php'>Library</a></li>
                        <li><a href='Logout.php'>Logout</a></li>
                    </ul>";
                    }
                    ?>
                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1>Frequently Asked Questions</h1>
                </div>
                <div id="ContentLeft">
                    <h4>This Page is constantly changing.</h4><br />
                    <h6>Check regularly for information on new site features.</h6>
                </div>
                <div id="ContentRight"><br />
                    <h4>What is Collective Shopping?</h4>
                    <h6><br />Collective Shopping is people leveraging their unified purchasing power to get deals. That way groups of people that would be buying the
                    same item anyway, can coordinate their buying to reap the same benefits as wholesale distributors. As groups converge on Products that
                    they all decide to support, the marketplace will become more organized and efficient overtime. By including the vendors and the
                    manufacturers in these conversations, we facilitate the exchange of information between consumers and companies.</h6>
                    <br />
                    <h4>Why use UPCs?</h4>
                    <h6><br />Universal Product Codes(UPCs) are already the global standard for goods in the consumer market. They distinguish every item by 
                        manufacturer, product, type, flavour, case size, container size, et cetera. Not to mention, now that everyone has a smartphone, we can
                        simply scan the barcodes directly off the packages.</h6>
                    <br />
                    <h4>Are the Ratings important?</h4>
                    <h6><br />Ratings for Items, Vendors and Manufacturers play very little role in how calculations are made on the site. How much they affect your
                        decision to participate in Deals is up to you. Admin and User Ratings on the other hand play a large role in how much Cred an Admin is 
                        awarded upon the completion of a Deal. The exact formula is a closely held secret, but generally, higher ratings result in more Cred.</h6>
                    <br />
                    <h4>Is the Raffle rigged?</h4>
                    <h6><br />True randomness only occurs with infinitely large datasets. The algorithms we use are nothing fancy. Any irregularities in the Raffling of 
                        Leftovers on the site is up to the host Admin's discretion to resolve. We suggest flipping coins, picking numbers or drawing straws. When 
                        technology fails us, the oldies are still the goodies.</h6>
                    <br />
                    <h4>Who cares about Cred?</h4>
                    <h6><br />Cred is Shopidarity's loyalty reward system. It is like a special currency that Admins can use on the site. It is accumulated as New items are
                        added to the UPC Library and as Deals are completed. Admins may then choose to claim Leftovers in their Deals by expending Cred. They 
                        must be careful though, people probably won't join their Deal if they are paying to enter a Raffle with no prizes. Although it may still be cheaper than going it alone.</h6>
                    <br />
                    <h4>Do companies pay to have their Deals prioritized?</h4>
                    <h6><br />Never! That is not how our site works at all. Every Deal on our site is submitted by an Admin. If an Admin is found to be submitting Deals 
                        using any form of employee priveledge, they will have their Admin status revoked immediately. Companies may certify a moderator account, 
                        for a fee, which allows them to provide product descriptions and images to be displayed only on the page that we have designated to them.</h6>
                <br />
                </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='#'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php if ($login == 1){ 
                if ($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else {echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                }?>
                <h6><br />(c) 2015 All Rights Reserved </h6></div>
        </div>
    </body>
</html>
