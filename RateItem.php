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
    $dbsuccess2 = ""; $dbErr3 = ""; $dbErr4 = "";
    $info = ""; $deals = "";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET['id'])){
    $itemId = strip_tags($_GET['id']);
    $ratecode = rateit.$itemId;
}
$f = 0;
if (!isset($_POST['Submit'])){
    $f = 1;
}
if (isset($_POST['Submit'])){
    if(isset($_POST['NewRate'])){
        $rate = strip_tags($_POST['NewRate']);
        if (0 <= $rate OR $rate <= 10){
            $valid++;
        }
    }
    if (isset($_POST['Comment'])){
        $comment = strip_tags($_POST['Comment']);
        if (is_string($comment)){
            if (strlen($comment) <= 144){
                $valid++;
            }
        }
    }
    if (isset($_POST['Public'])){
        $public = strip_tags($_POST['Public']);
        if ($public == '1' OR $public == '0'){
            $valid++;
        }
    }
}

if ($valid == 3){
    $addrate = "INSERT INTO `{$ratecode}` (`UserId`, `Rating`, `Comment`, `Public`) VALUES ('$userId', '$rate', '$comment', '$public')";
    if (mysqli_query($dbconnect, $addrate)){
        $allrates = "SELECT * FROM `{$ratecode}` WHERE `Delete` = '0'";
        $rquery = mysqli_query($dbconnect, $allrates);
        $numrates = mysqli_num_rows($rquery);
        $sumrate = "SELECT SUM(`Rating`) from`{$ratecode}` WHERE `Delete` = '0'";
        $sum = mysqli_fetch_row(mysqli_query($dbconnect,$sumrate));
        $newrate = $sum[0]/$numrates;
        $changerate = "UPDATE `upclib` SET `AvRate` = '$newrate' WHERE `ID` = '$itemId'";
        if (mysqli_query($dbconnect, $changerate)){
            $dbsuccess2 = "Rating added";
        }
    }
}
$checkuser = "SELECT * FROM `{$ratecode}` WHERE `UserId` = '$userId' AND `Delete` = '0'";
$rateyet = 0;
$uquery = mysqli_query($dbconnect, $checkuser);
if (mysqli_num_rows($uquery) != 0){
    $rateyet = 1;
}
$iteminfo = "SELECT * FROM `upclib` WHERE `ID` = '$itemId'";
if ($row = mysqli_fetch_assoc(mysqli_query($dbconnect, $iteminfo))){
    $item = $row['ItemName']; $upc = $row['UPC'];
    $company = $row['Company']; $manuId = $row['ManuId'];
    $oldrate = $row['AvRate'];
    $rateinfo = "SELECT * FROM `{$ratecode}` WHERE `Public` = '1' AND `Delete` = '0'";
    if (mysqli_fetch_assoc(mysqli_query($dbconnect, $rateinfo))){
        $dbsuccess = "Item Ratings";
    } else {
        $dbErr1 = "No connection";
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
        <div id="Lside">Hello</div>
        <div id="Rside">World</div>
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
                    <h1><?php echo $item; ?>'s Ratings</h1>
                </div>
                <div id="ContentLeft">
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2>
                    <br /><h4>Rating: <b><?php echo $row["AvRate"]; ?></b></h4>
                    <br />
                    <table>
                        <?php 
                        while($row != '') {
                            echo "<tr><td><h6><br /><b>UPC:<b></h6></td><td><h6><br /><a href = 'ItemPage.php?id=".$itemId."'>".$row["UPC"]."</a></h6></td>";
                            echo "<tr><td><h6><br /><b>Product:<b></h6></td><td><h6><br />".$row["ItemName"]."</h6></td>";
                            echo "<tr><td><h6><br /><b>Case Size:<b></h6></td><td><h6><br />".$row['CaseSize']."</h6></td>";
                            echo "<tr><td><h6><br /><b>Manufacturer:<b></h6></td><td><h6><br /><a href = 'Manupage.php?id=".$row["ManuId"]."'>".$row["Company"]."</a></h6></td>";
                            echo "<tr><td><h6><br /><b>Category:<b></h6></td><td><h6><br />".$row["Category"]."</h6></td>";
                            echo "<tr><td><h6><br /><a href='#'>Report Error</a></h6></td>";
                            $row= '';
                        }
                        ?> 
                    </table><br/>
                </div>
                <div id="ContentRight">
                    <?php
                    echo "<h4>".$dbsuccess1.$dbErr3."</h4>";
                    if ($rateyet == 0){
                        echo "<table>
                            <tr><td><h4><br/>Rate Item:</h4>
                        <br />
                        <form method ='POST' action='RateItem.php?id=".$itemId."'>
                            <select name ='NewRate'>
                                <option value ='0'";
                                if ($rate == 0){echo "selected = 'true'";}
                                echo ">0</option>
                                <option value ='1'";
                                if ($rate == 1){echo "selected = 'true'";}
                                echo ">1</option>
                                <option value ='2'";
                                if ($rate == 2){echo "selected = 'true'";}
                                echo ">2</option>
                                <option value ='3'";
                                if ($rate == 3){echo "selected = 'true'";}
                                echo ">3</option>
                                <option value ='4'";
                                if ($rate == 4){echo "selected = 'true'";}
                                echo ">4</option>
                                <option value ='5' ";
                                if ($f == 1){echo "selected = 'true'";}
                                else if ($rate == 5){echo "selected = 'true'";}
                                echo ">5</option>
                                <option value ='6'";
                                if ($rate == 6){echo "selected = 'true'";}
                                echo ">6</option>
                                <option value ='7'";
                                if ($rate == 7){echo "selected = 'true'";}
                                echo ">7</option>
                                <option value ='8'";
                                if ($rate == 8){echo "selected = 'true'";}
                                echo ">8</option>
                                <option value ='9'";
                                if ($rate == 9){echo "selected = 'true'";}
                                echo ">9</option>
                                <option value ='10'";
                                if ($rate == 10){echo "selected = 'true'";}
                                echo ">10</option>
                            </select></td><td>
                            </td></tr>
                            <tr><td><br />
                            <h4>Comment:</h4><br/>
                            <textarea name='Comment' cols= '35' rows = '5' maxlength='144'>";
                            echo $comment;
                            echo "</textarea>
                            </td><td>
                            </td></tr>
                            <tr><td><br/>
                            <h4>Make Rating Public?<h4><br />
                            <h6><input type = 'radio' name = 'Public' value= '1'/>Yes
                            <input type = 'radio' name = 'Public' value= '0'/>No</h6>
                            <br /> 
                            <input type='submit' name='Submit' value='Rate'/>
                            </td><td>
                            </td></tr>
                        </form></table>";
                    } else {
                        echo "<h4><br />Aleady Rated this Item.</h4>
                        <h2><br/>Your Rating:</h2>";
                        $urate = mysqli_fetch_assoc($uquery);
                        echo "<br/><table>
                        <thead><td><h4>Rating</h4></td><td><h4>Comment</h4></td><td><h4>Time</h4></td></thead>
                        <tr><td><h6>".$urate['Rating']."</h6></td><td><h6>".$urate['Comment']."</h6></td><td><h6>".$urate['Time']."</h6></td></tr></table>";
                        echo "<h6><br/><a href = 'EditRateIt.php?id=".$itemId."'>Edit?</a></h6>";
                        
                    }
                    ?>
                    <h2><br />Other User's Ratings:</h2><br />
                    <?php
                    echo "<table><thead><td><h4>Rating</h4></td><td><h4>Comment</h4></td><td><h4>Time</h4></td></thead>";
                    $getrate = mysqli_query($dbconnect, $rateinfo);;
                    while ($row2 = mysqli_fetch_assoc($getrate)){
                        echo "<tr><td><h6><br />".$row2['Rating']."</h6></td><td><h6><br />".$row2['Comment']."</h6></td><td><h6><br />".$row2['Time']."</h6></td></tr>";
                    } echo "</table>";
                    ?>
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

