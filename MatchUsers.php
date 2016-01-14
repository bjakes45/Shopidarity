<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $dbsuccess = ""; $dbErr1 = ""; $dbErr2 = "";
    $info = "";
} else {
    header("Location:index.php");
    die();
}
$cup = cup.$userId;
$i = 0; 
$nquery = "SELECT max(id) FROM login";
$numusers = mysqli_fetch_row(mysqli_query($dbconnect, $nquery));
while ($i <= $numusers[0]){
    if ($i == $userId){$i++;}
    $cyclecup = cup.$i;
    $match[$i] = 0;
    $sql = "SELECT LibId FROM `{$cup}`";
    $csql = mysqli_query($dbconnect, $sql);
    $f = 0;
    while ($f < mysqli_num_rows($csql)) {
        $row = mysqli_fetch_assoc($csql);
        $libId = $row['LibId'];
        $query = "Select `LibId` FROM `{$cyclecup}` WHERE LibId = '$libId' LIMIT 1";
        if ($mquery = mysqli_query($dbconnect, $query)){
            if(mysqli_num_rows($mquery) == 1){$match[$i]++;}
        }
    $f++;
    }
    $i++;
}
$nmatches = count($match);

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
                    <h1><?php echo $username; ?>'s Matches</h1>
                </div>
                <div id="ContentLeft">
                    <ul>
                    <li><a href="#">By User</a></li>
                    <li><a href="MatchItems.php">By Item</a></li>
                    <li><a href="MatchLoc.php">By Location</a></li>
                    </ul>
                </div>
                <div id="ContentRight">
                    <?php
                    echo "<table><thead><td><h4>User</h4></td><td><h4>Matches</h4></td></thead>";    
                    for ($x=0; $x <= $numusers[0]; $x++){
                        if ($match[$x] != 0) {
                            if ($x != $userId){
                                $msql = "Select * FROM login where id = '$x'";
                                $matchuser = mysqli_fetch_assoc(mysqli_query($dbconnect, $msql));
                                echo "<tr><td><h6><br />".$matchuser['username']."</h6></td><td><h6><br />".$match[$x]."</h6></td></tr>";
                            } else { echo "No matches!";}
                        }
                    }
                    echo "</table>";
                    ?>
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


