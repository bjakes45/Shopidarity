<script>
function SendVend(str) {
    $.post("NewDeal.php", {$vendor = 'str'} );
    $.post("NewDeal.php", 'submit')
}
</script>
<?php
include_once 'Dbconnect.php';
if (isset($_GET["q"])){
    $q =  strip_tags($_GET["q"]);
    if (strlen($q)>0) {
        $vendSearch = mysqli_query($dbconnect, "SELECT * FROM vendor WHERE VendName LIKE '%{$q}%' LIMIT 5");
        if (mysqli_num_rows($vendSearch)!= 0){
            
            while ($row = mysqli_fetch_assoc($vendSearch)){
                echo "<a href = '#' onlick = 'SendVend(this.value)'><h6>".$row['VendName']."</h6></a>";
            }
            
        } else {
            echo "<h4>Vendor not Recognized</h4><h6><br />You may Submit a New Vendor for Confirmation</h6><h6><br />Or Continue with an Unconfirmed Vendor</h6>";
            echo "<br /><form method = 'POST' action='NewDeal.php'><input type= 'submit' name= 'NewVendor' value='New Vendor'> <input type= 'submit' name= 'Unconfvend' value='Unconfirmed'></form>";
        }
    }
}
?>

