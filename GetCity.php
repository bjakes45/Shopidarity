<!DOCTYPE html>
<html>
    <head></head>
    <body>
<?php
include_once 'Dbconnect.php';
if (isset($_GET['q'])){
    $q = strip_tags($_GET['q']);
    $getcity = "SELECT `City` FROM $q";
    $dbcity = mysqli_query($dbconnect, $getcity);
    $numcity = mysqli_num_rows($dbcity);                    
    echo "<td><br /><h6>City</h6></td>";
    echo "<form method = 'post' action = 'Register.php'><select name= 'Location'>";
    echo "<option value=''>Select</option>";
    $e = 0;
    while ($e < $numcity) {
        $row2 = mysqli_fetch_row($dbcity);
        $dlocation = $row2[0];
        echo "<option ";
        echo "value='".$dlocation."'>".$dlocation."</option>";
        $e++;
        }
    echo "</select></form><td>";
}
else if(isset($_GET['p'])){
    $p = strip_tags($_GET['p']);
    $getcity = "SELECT `City` FROM `{$p}`";
    $dbcity = mysqli_query($dbconnect, $getcity);
    $numcity = mysqli_num_rows($dbcity);                    
    echo "<td><br /><h6>City</h6></td>";
    echo "<form method = 'post' action = 'EditProfile.php'><select name= 'Location'>";
    echo "<option value=''>Select</option>";
    $e = 0;
    while ($e < $numcity) {
        $row2 = mysqli_fetch_row($dbcity);
        $dlocation = $row2[0];
        echo "<option ";
        echo "value='".$dlocation."'>".$dlocation."</option>";
        $e++;
        }
    echo "</select></form><td>";
}
else if(isset($_GET['r'])){
    $r = strip_tags($_GET['r']);
    $getcity = "SELECT `City` FROM `{$r}`";
    $dbcity = mysqli_query($dbconnect, $getcity);
    $numcity = mysqli_num_rows($dbcity);                    
    echo "<td><br /><h6>City</h6></td>";
    echo "<form method = 'post' action = 'NewDeal.php'><select name= 'Location'>";
    echo "<option value=''>Select</option>";
    $e = 0;
    while ($e < $numcity) {
        $row2 = mysqli_fetch_row($dbcity);
        $dlocation = $row2[0];
        echo "<option ";
        echo "value='".$dlocation."'>".$dlocation."</option>";
        $e++;
        }
    echo "</select></form><td>";
}
?>
    </body>
</html>
