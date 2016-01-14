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
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $typeErr1 = ""; $typeErr2 = "";
    $searchErr1 = ""; $searchErr2 = "";
    $result = ""; $valid = 0;
}
if (isset($_POST["Confirm"])){
    if (empty($_POST['SearchType'])) {
        $typeErr1 = "Please enter a Search Type";
    } else {
        if (isset($_POST['SearchType']) == true && empty($_POST['SearchType']) == false) {
            $safetype = strip_tags($_POST['SearchType']);
                if (preg_match("/^[a-zA-Z ]*$/",$safetype)) {
                    $valid++;
                } else {
                    $typeErr2 = "Please enter a valid Product Name";
                }
        }
    }
    if (empty($_POST['Search'])) {
        $searchErr1 = "Please enter a Search";
    } else {
        if (isset($_POST['Search']) == true && empty($_POST['Search']) == false) {
            $safesearch = strip_tags($_POST['Search']);
            if ($safetype == 'UPC'){    
                if (strlen($safesearch) > 9 && strlen($safesearch) < 14) {
                    $valid++;
                } else {
                    $searchErr2 = "Please enter a valid UPC";
                }
            } else {    
                if (preg_match("/^[a-zA-Z ]*$/",$safesearch)) {
                    $valid++;
                } else {
                    $searchErr2 = "Please enter a valid Search";
                }
            }
        }
    }
    if ($valid > 1) {
        
        $searchlib = "SELECT * FROM upclib WHERE `{$safetype}` LIKE '%$safesearch%' AND Verified = 1";
        $result = mysqli_query($dbconnect, $searchlib);
        if (mysqli_num_rows($result)!= 0){
            $dbsuccess = "Results:"; 
        } else {
            $dbErr1 = "Result Empty";
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
function searchType(str) {
    if (str == "") {
        document.getElementById("SearchInput").innerHTML = "";
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
                document.getElementById("SearchInput").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","SearchLibajax.php?c="+str,true);
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
                    <h1>The UPC Library</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="ViewLibrary.php">UPC Library</a></li>
                    <li><a href="#">Search</a></li>
                    <li><a href="Matches.php">Matches</a></li>
                    </ul>
                    <div style="margin-right: 35px;">
                     <?php if ($admin != 1) {
                    echo "<h6>Note: If you cannot find an item in the UPC Library,</h6>
                    <h6>Please request to become an Admin.</h6>
                    <h6>Find the link on your <a href='Account.php'>Profile</a>,</h6>
                    <h6>Answer a few questions.</h6>
                    <h6>Then help us build our Library!</h6>";
                    } else {
                        echo "<h6>Thank you for being an Admin.</h6>
                        <h6>You can now Add Items and Create Deals.</h6><br />
                        <h6>To access the Admin section of the site,</h6>
                        <h6>Find the tab that appears on your <a href='Account.php'>Profile</a>.</h6>";
                    }
                    ?></div>
                </div>
                <div id="ContentRight">
                    
                         <?php 
                        echo "<h2>$dbsuccess</h2><br />";
                        if ($result != ""){
                            if (mysqli_num_rows($result)!= 0){  
                                echo "<table border ='0'><thead><td><h4>UPC</h4></td><td><h4>Product</h4></td><td><h4>Case</h4></td><td><h4>Company</h4></td><td><h4>Category</h4></td><td><h4>Add?</h4></td></thead>";
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr><td><h6><br /><a href='ItemPage.php?id=".$row['ID']."'>".$row["UPC"]."</a></h6></td>";
                                    echo "<td><h6><br />".$row["ItemName"]."</h6></td>";
                                    echo "<td><h6><br />".$row["CaseSize"]."</h6></td>";
                                    echo "<td><h6><br /><a href='ManuPage.php?id=".$row["ManuId"]."'>".$row["Company"]."</a></h6></td>";
                                    echo "<td><h6><br />".$row["Category"]."</h6></td>";
                                    echo "<td><h6><br /><a href='AddItem.php?id=" . $row['ID'] . "'>Add?</a></h6></td></tr>";
                                }
                                echo "</table><br /><br /><h2>Search again?</h2>";
                            }
                        }
                    ?>
                    <form method="POST" action="SearchLib.php">
                        <h4><?php echo "$dbErr1";?></h4><br/>
                                <h4>Search By:</h4><br />
                                <select name="SearchType" onchange="searchType(this.value)">
                                    <option value="">Select</option>
                                    <option value="UPC">UPC</option>
                                    <option value="ItemName">Product</option>
                                    <option value="Company">Company</option>
                                    <option value="Category">Category</option>
                                        </select>
                                <h4><?php echo "$typeErr1"; echo "$typeErr2";?></h4>
                            <div id='SearchInput'></div>
                            <br /><h4><?php echo $searchErr1.$searchErr2; ?></h4>
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


