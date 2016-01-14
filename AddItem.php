<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $cup = cup.$userId;
    $item = ""; $valid = 0; $off = 0;
    $dbsuccess = ""; $dbErr1 =""; $dbErr2 ="";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["id"])) {
    $safeId = strip_tags($_GET["id"]);
    $query = "SELECT * FROM upclib WHERE ID = '$safeId' LIMIT 1";
    $item = mysqli_fetch_assoc(mysqli_query($dbconnect, $query));
    if (!empty($item)) {
        $libid = $item['ID']; $upc = $item['UPC']; $manuId = $item["ManuId"];
        $itemname = $item['ItemName']; $case = $item['CaseSize'];
        $company = $item['Company']; $category = $item['Category'];
        $checkupc = "SELECT * FROM `{$cup}` WHERE `UPC` = '$upc' ";
        if (mysqli_num_rows(mysqli_query($dbconnect, $checkupc)) == 0) {
            $valid++;
        } else {
            $upcErr1 = "UPC Already in Cupboard";
            $off = 1;
        }
        if (isset($_POST["Add"])) {
            if (empty($_POST['PurFreq'])) {
            $purErr1 = "Please enter a Purchase Frequency";
            } else {
                if (isset($_POST['PurFreq']) == true && empty($_POST['PurFreq']) == false) {
                    $purfreq = strip_tags($_POST['PurFreq']);
                        if (preg_match("/^[a-zA-Z ]*$/",$purfreq)) {
                            $valid++;
                        } else {
                            $purErr2 = "Please enter a valid Purchase Frequency";
                        }
                }
            }
            if ($valid == 2) {
                    $add = "INSERT INTO `{$cup}`(`UPC`, `ItemName`, `CaseSize`, `Company`, `ManuId`, `Category`, `PurFreq`, `LibId`) VALUES('$upc','$itemname','$case','$company','$manuId','$category','$purfreq', '$libid')";;
                    if(mysqli_query($dbconnect, $add)) {
                        $dbsuccess = "Item added!";
                    } else {
                        $dbErr2 = "Something went wrong!";
                    }
            } else {
                $dbErr1 = "Didn't Connect.";
                $off = 1;
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
                <div id="PageHeading">
                    <h1>The UPC Library</h1>
                </div>
                <div id="ContentLeft">
                </div>
                <div id="ContentRight">
                    <h6><?php if (!isset($_POST["Add"])) {if ($off == 0) {echo "Do you really want to add ".$item['ItemName']." to your cupboard?";}} echo $dbErr1; echo $dbErr2; echo $dbsuccess; echo $upcErr1?></h6><br />
                    <form method="POST" action="AddItem.php?id=<?php echo $safeId ?>">
                        <table>
                            <tr>
                                <td><h6>Purchase Frequency</h6>
                                    <select name="PurFreq">
                                        <option value="">Select</option>
                                        <option value="Once">Only once</option>
                                        <option value="Daily">Daily</option>
                                        <option value="FewDays">Every Few Days</option>
                                        <option value="Week">Every Week</option>
                                        <option value="Biweekly">Biweekly</option>
                                        <option value="Month">Every Month</option>
                                        <option value="Whenever">Whenever</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td><span class="error">*<?php echo "$purErr1"; echo "$purErr2"; ?></span></td>
                            </tr>
                            <tr>
                                <td><br />
                                    <input type="submit" name="Add" value="Add Item"><h6>or<a href="ViewLibrary.php">Return to Library</h6>
                                </td>
                            </tr>
                        </table>    
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

