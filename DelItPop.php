<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $item = "";
    $dbsuccess = ""; $dbErr1 ="";
} else {
    header("Location:index.php");
    die();
}
if (isset($_GET["id"])) {
    $safeId = strip_tags($_GET["id"]);
    $cup = cup.$userId;
    $query = "SELECT ItemName FROM `{$cup}` WHERE `LibId` = '$safeId' LIMIT 1";
    $item = mysqli_fetch_assoc(mysqli_query($dbconnect, $query));
    if (!empty($item)) {
        if (isset($_POST["Delete"])) {
            $delete = "DELETE FROM `{$cup}` WHERE `LibId` = '$safeId'";
                if(mysqli_query($dbconnect, $delete)) {
                    $dbsuccess = "Item deleted!";
                } else {
                    $dbErr2 = "Something went wrong!";
                }
        }
    } else {
        $dbErr1 = "Didn't Connect."; 
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
        <script src="jquery-1.11.3.min.js"></script>
    <script type="text/javascript">
function DelIt(str) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $.post("DelItPop.php?id="+str, "Delete");
                document.getElementById("popup-container").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","DelItPop.php?id="+str,true);
        xmlhttp.send();
    };
        };
</script>
    </head>
    <body><h6><?php if (!isset($_POST["Delete"])) { echo $username." do you really want to delete ".$item['ItemName']." from your cupboard?";} echo $dbErr1;echo $dbErr2; echo $dbsuccess?></h6><br />
                    <form method="POST" action="#">
                        <input type="button" name="Delete" value="Delete Item" onclick='DelIt("<?php echo $safeId;?>")'><br />
                    </form>
        
</body>
</html>