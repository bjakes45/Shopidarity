<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
} else {
    header("Location:index.php");
    die();
}
if(isset($_GET["id"])){
    $vendId = strip_tags($_GET["id"]);
    $vendinfo = "SELECT * FROM vendor WHERE ID = '$vendId'";
    if ($info = mysqli_fetch_assoc(mysqli_query($dbconnect, $vendinfo))){
        $vendname = $info['VendName'];
        $vendrate = $info['AvRate'];
        $vendcode = vend.$vendId;
        $getdeals = "SELECT  * FROM `{$vendcode}` WHERE Active = 1";
        $deals = mysqli_query($dbconnect, $getdeals);
        if ($deals != "") {
            if (mysqli_fetch_assoc($deals)){
                $a = 1;
                $dbsuccess = "Their Deals"; 

            } else {
            $dbErr2 = "Deals Empty";
            }
        } else { 
            $dbErr1 = "Couldn't Display";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">
        <link href="layout.css" rel="stylesheet" type="test/css" />
        <link href="menu.css" rel="stylesheet" type="test/css" />
        <meta charset="UTF-8">
        <title>SHOPIDARITY | Share & Save</title>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYq17TtufC2Fw6v7u-bJw0CguORSCUSLw"></script>
        <script>
  function initialize() {
    var mapCanvas = document.getElementById('map-canvas');
    var myCenter = new google.maps.LatLng(49.2827, -123.1207);
    var mapOptions = {
      center: myCenter,
      zoom: 11,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = new google.maps.Marker({
  position:myCenter,
  });
marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"No Locations Listed"
  });
infowindow.open(map,marker);
google.maps.event.addListener(map,'center_changed',function() {
// 3 seconds after the center of the map has changed, pan back to the marker
  window.setTimeout(function() {
    map.panTo(marker.getPosition());
  },3000);
  });
  }
  
  google.maps.event.addDomListener(window, 'load', initialize);
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
                    <h1><?php echo $vendname; ?> Deals</h1>
                </div>
                <div id="ContentLeft">
                    <div id="CompanyPic"><img src="Default.png" alt="Product Image"></div>
                    <h4>Rating: <b><?php echo $vendrate; ?></b></h4>
                    <h6><br />Vendors may only be Rated</h6>
                    <h6> after you complete a Deal there.</h6>
                    <h6><br /><br /><br />Do you represent this Company?</h6>
                    <h6><br /><br/>Take control of what content is displayed here.</h6>
                    <h6><br />Attract users to make Deals at your store(s)!</h6>
                    <h6><br/>Add Branding and Location info to this page.</h6>
                    <h6>To make it more attractive to our Users.</h6>
                    <h6><br /><a href = "Contact.php">Apply to Certify</a> your Company for a monthly fee now!</h6>
                </div>
                <div id="ContentRight"><br/>
                    <div id="map-canvas"></div>
                    <h2><br/><?php echo "$dbsuccess"; echo "$dbErr1"; echo "$dbErr2"; ?></h2><br />
                    </div>
            </div>
            <div id="Footer"><br /><br/>
                <h6><a href='FAQ.php'>FAQ</a></h6>
                <h6><a href='Contact.php'>Contact Us</a></h6>
                <?php 
                if($admin == 1){ echo "<h6><a href='#'>Become a Manager</a></h6>"; }
                else if($admin == 0){echo "<h6><a href='RequestAdmin.php'>Become an Admin</a></h6>"; }
                ?>
                <h6><br />(c) 2015 All Rights Reserved</h6></div>
        </div>
    </body>
</html>


