<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $noti = noti.$userId;
    $checknoti = "SELECT * FROM `{$noti}` WHERE `Seen` = '0' AND `Delete` = '0'";
    $numnew = mysqli_num_rows(mysqli_query($dbconnect, $checknoti));
    $items = "";
} else {
    header("Location:index.php");
    die();
}
$cup = cup.$userId;
$getitems = "SELECT * FROM $cup";
$items = mysqli_query($dbconnect, $getitems);
if ($items != "") {
    if (mysqli_num_rows($items)!= 0){
        $a = 1;
        $dbsuccess = "Your Items"; 
    } else {
    $dbErr2 = "Cupboard Empty";
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
        <title>SHOPIDARITY | Shop & Save</title>
        <script >
    
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    };
    function refreshpop(str) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("popup-container").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","DelItPop.php?id="+str,true);
        xmlhttp.send();
    };
</script>
    </head>
    <body>
        <div id = 'ConfirmDel' class = 'popup-position'>
            <div id='popup-wrapper'>
                <div style='float: left; margin-left: 3px;'><h4>Confirm Delete</h4></div>
                <div style=
                     "margin-right: 3px;
                     float: right;">
                </div><a href='javascript: void(0)' onclick = "toggle_visibility('ConfirmDel');">
                    <img src="close.png"></a>
                <div id='popup-container'>
                    <?php
                    include 'DelItPop.php';
                    ?>   
                </div>
            </div>
        </div>
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
                    <h1><?php echo $username; ?>'s Cupboard</h1>
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
                    <li><a href="#">Your Cupboard</a></li>
                    <li><a href="MyDeals.php">Deal Center</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <h2><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2";?></h2><br />
                    <table border ="0" style="">
                        <?php if ($a == 1){
                        echo "<thead><td><h4>UPC  </h4></td><td><h4>Product  </h4></td><td><h4>Case  </h4></td><td><h4>Company  </h4></td><td><h4>Category  </h4></td><td><h4>Frequency  </h4></td><td><h4>Delete?</h4></td></thead>"; 
                            while($row = mysqli_fetch_assoc($items)) {
                                echo "<tr><td><h6><br/><a href='ItemPage.php?id=" . $row['LibId'] . "'>".$row["UPC"]."</a></h6></td>";
                                echo "<td><h6><br/>".$row["ItemName"]."</h6></td>";
                                echo "<td><h6><br/>".$row["CaseSize"]."</h6></td>";
                                echo "<td><h6><br/><a href ='ManuPage.php?id=".$row['ManuId']."'>".$row["Company"]."</a></h6></td><td>";
                                echo "<h6><br/>".$row["Category"]."</h6></td>";
                                echo "<td><h6><br/>".$row["PurFreq"]."</h6></td>";
                                echo "<td><h6><br/><a href= '#' onclick = ".'refreshpop('.$row["LibId"].')'.";".'toggle_visibility("ConfirmDel")'.">Delete?</h6></td></tr>";
                            }
                        }
                        ?>
                    </table>
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

