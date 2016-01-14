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
} else {
    header("Location:index.php");
    die();
}
//validate
if (isset($_GET["item"])) {
    $itemId = strip_tags($_GET["item"]);
} else {
}
if (isset($_GET["id"])) {
    $dealId = strip_tags($_GET["id"]);
}
if (isset($_GET["u"])) {
    $u = strip_tags($_GET["u"]);
    $dealcode = deal.$itemId._.$dealId;
    $getparts = "SELECT `UserId` FROM `{$dealcode}` WHERE `Approved` != '0'";
    $pquery = mysqli_query($dbconnect, $getparts);
    $n=1;
    while($part = mysqli_fetch_array($pquery)){
        $partId[$n] = $part[0];
        $n++;
    }
    $numquery = mysqli_num_rows($pquery);
    $ratecode = rateus.$partId[$u];
    $rateinfo = "SELECT * FROM `{$ratecode}` WHERE `Public` = '1'";
    } else {
    header("Location:CompleteSurvey.php?item=".$itemId."&id=".$dealId);
    die();
}

$a = 0;
if (!isset($_SESSION['MaxRate'])){
    if(isset($_POST["Submit"])){
        if(isset($_POST["GoodInfo"])){
           if(($_POST["GoodInfo"]) == 1) {
               $a++;
           }
        } else {
        header("Location:CompleteSurvey.php?item=".$itemId."&id=".$dealId."&e=1");
        die();
        }
        if(isset($_POST["GotUnits"])){
            if(($_POST["GotUnits"]) == 1) {
               $a++;
            }
        } else {
        header("Location:CompleteSurvey.php?item=".$itemId."&id=".$dealId."&e=2");
        die();
        }
        if(isset($_POST["GoodCost"])){
            if(($_POST["GoodCost"]) == 1) {
               $a++;
            }
        } else {
        header("Location:CompleteSurvey.php?item=".$itemId."&id=".$dealId."&e=3");
        die();
        }
        if ($a == 0){$_SESSION['MaxRate'] = 0;}
        if ($a == 1){$_SESSION['MaxRate'] = 1;}
        if ($a == 2){$_SESSION['MaxRate'] = 2;}
        if ($a == 3){$_SESSION['MaxRate'] = 3;}
    } else {
        header("Location:CompleteSurvey.php?item=".$itemId."&id=".$dealId."&e=0");
        die();
    }
}
$f = 0;
if (!isset($_POST['Rate'])){
    $f = 1;
}
if (isset($_POST['Rate'])){
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
        $allrates = "SELECT * FROM `{$ratecode}`";
        $rquery = mysqli_query($dbconnect, $allrates);
        $numrates = mysqli_num_rows($rquery);
        $sumrate = "SELECT SUM(`Rating`) FROM `{$ratecode}`";
        $sum = mysqli_fetch_row(mysqli_query($dbconnect,$sumrate));
        $newrate = $sum[0]/$numrates;
        $changerate = "UPDATE `login` SET `AvRate` = '$newrate' WHERE `id` = '$partId[$u]'";
        if (mysqli_query($dbconnect, $changerate)){
            $dbsuccess2 = "Rating added";
        } else{
            $dbErr1 = "No average";
        }
    } else {
        $dbErr1= "Rating not added";
    }
}
$checkuser = "SELECT * FROM `{$ratecode}` WHERE `UserId` = '$userId'";
$rateyet = 0;
$uquery = mysqli_query($dbconnect, $checkuser);
if (mysqli_num_rows($uquery) != 0){
    $rateyet = 1;
}
$partinfo = "SELECT * from login WHERE id = '$partId[$u]'";
$pinfo = mysqli_fetch_assoc(mysqli_query($dbconnect, $partinfo));
$partrate = $pinfo['AvRate'];
$partname = $pinfo['username'];
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
                    <h1><?php echo $partname;?> Ratings
                        </div>
                <div id="ContentLeft">
                    <h2><?php echo $dbsuccess; echo $dbErr1; echo $dbErr2;?></h2>
                    <br /><h4>Rating: <b><?php echo $partrate; ?></b></h4>
                    <br />
                    <br /><h6>Your survey answers have limited the ratings that you can choose from.</h6>
                    <br /><h6>If you would like to change your survey answers <a href = 'CompleteSurvey.php?item=<?php echo $itemId;?>&id=<?php echo $dealId;?>&re=1'>Click Here</a></h6>
                        <?php 
                        
                    ?>
                    </div>
                <div id="ContentRight">
                    <?php
                    echo "<h4>".$dbsuccess2.$dbErr3."</h4>";
                    if ($rateyet == 0){
                        echo "<table>
                            <tr><td><h4><br/>Rate User</h4>
                        <br />
                        <form method ='POST' action='RateUser.php?item=".$itemId."&id=".$dealId."&u=".$u."'>";
                        if ($_SESSION['MaxRate']==3) {
                        echo "<select name ='NewRate'>
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
                            </select>";
                        } else if ($_SESSION['MaxRate']==2) {
                        echo "<select name ='NewRate'>
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
                            </select>";
                        } else if ($_SESSION['MaxRate']==1) {
                        echo "<select name ='NewRate'>
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
                            </select>";
                        } else if ($_SESSION['MaxRate']==0) {
                        echo "<select name ='NewRate'>
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
                            </select>";
                        } 
                        echo "</td><td>
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
                            <input type='submit' name='Rate' value='Rate'/>
                            </td><td>
                            </td></tr>
                        </form></table>";
                    } else {
                        echo "<h4>Aleady Rated this user.</h4>
                        <h4><br/>Your Rating:</h4>";
                        $urate = mysqli_fetch_assoc($uquery);
                        echo "<br/><table>
                        <thead><td><h4>Rating</h4></td><td><h4>Comment</h4></td><td><h4>Time</h4></td></thead>
                        <tr><td><h6>".$urate['Rating']."</h6></td><td><h6>".$urate['Comment']."</h6></td><td><h6>".$urate['Time']."</h6></td></tr></table>";
                        echo "<h6><br/><a href = '#'>Edit?</a></h6>";
                        if ($u < $numquery){
                            echo "<br /><form action='RateUser.php?item=".$itemId."&id=".$dealId."&u=".($u+1)."' method='POST'>
                                <input type='submit' name='Submit' value='Next Participant'/></form>
                                <h4><br />".$u."/".$numquery." Participants</h4>";
                        } else {
                            echo "<br /><form action='RateVendor.php?item=".$itemId."&id=".$dealId."' method='POST'>
                                <input type='submit' name='Submit' value='Rate Vendor'/></form>
                                <h4><br />".$u."/".$numquery." Participants</h4>
                                <h6>Last Step!</h6>";
                        }
                    }
                    ?>
                    <h2><br />Past Ratings:</h2><br />
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
