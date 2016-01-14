<!DOCTYPE html>
<html>
    <head></head>
    <body>
<?php
include_once 'Dbconnect.php';
if (isset($_GET['c'])){
    $c = strip_tags($_GET['c']);
    if ($c == 'UPC'){
        echo "<form method = 'post' action = 'SearchLib.php'>
            <td><br /><h4>For:</h4><br />
        <input type='text' name='Search'/>
        <h6><a href='ScanCode.php'>Scan</a></h6>
        <br /><br />
        <input type='submit' name='Confirm' value='Search'>
        </td></form>";
    } else if ($c != 'Category') {
        echo "<form method = 'post' action = 'SearchLib.php'>
            <td><br /><h4>For:</h4><br />
        <input type='text' name='Search'/>
        <br /><br />
        <input type='submit' name='Confirm' value='Search'>
        </td></form>";
    }
    else if ($c == 'Category'){
        echo "<form method = 'post' action = 'SearchLib.php'>
            <td><br /><h4>For:</h4><br />
                <select name='Search'>
                    <option value=''>Select</option>
                    <option value='Food'>Food</option>
                    <option value='House'>Household</option>
                    <option value='Health'>Health</option>
                    <option value='Outdoor'>Outdoor</option>
                    <option value='Accessories'>Accessories</option>
                    <option value='Other'>Other</option>
                </select>
                <br /><br />
            <input type='submit' name='Confirm' value='Search'>
        </td></form>";
    }
}
?>
    </body>
</html>