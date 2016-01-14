<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();

if (isset($_SESSION["id"])){
    include_once 'Dbconnect.php';    
    $userId = $_SESSION["id"];
    $username = $_SESSION["username"];
    $cup = cup.$userId;
    $admin = 2;
    $getadmin = "SELECT `admin` FROM `login` WHERE `id` ='$userId' LIMIT 1";
    $adminstatus = mysqli_fetch_array(mysqli_query($dbconnect, $getadmin));
        if ($adminstatus[0]) {
            $admin = 1;
        } else {
            $admin = 0;
        }
    if ($admin == 0) {
        header("Location:index.php");
        die();
    }
} else {
    header("Location:index.php");
    die();
}
if (isset($_POST["submit"])) {
    $valid = 0;
    $upcErr1 = ""; $upcErr2 = ""; $upcErr3; $upcErr4;
    $itemErr1 = ""; $itemErr2 = "";
    $companyErr1 = ""; $companyErr2 = "";
    $categoryErr1 = ""; $categoryErr2 = "";
    $dbErr1 = ""; $dbsuccess = "";
    if (empty($_POST['UPC'])) {
        $upcErr1 = "Please enter a UPC Code";
    } else {
        if (isset($_POST['UPC']) == true && empty($_POST['UPC']) == false) {
            $upc = strip_tags($_POST['UPC']);
                if (strlen($upc) > 9 && strlen($upc) < 14) {
                    $checkupc = "SELECT * FROM `{$cup}` WHERE upc = '$upc' LIMIT 1";
                    if (mysqli_num_rows(mysqli_query($dbconnect, $checkupc)) != 1) {
                    $checkupclib = "SELECT * FROM upclib WHERE upc = '$upc' AND Verified = 1 LIMIT 1";
                        if (mysqli_num_rows(mysqli_query($dbconnect, $checkupclib)) != 1) {
                            $valid++;
                        } else {
                            $upcErr4 = "UPC already in library";
                        }
                    } else {
                    $upcErr3 = "UPC already in Cupboard";
                    } 
                } else {
                    $upcErr2 = "Please enter a valid UPC";
                }
        }
    }
    if (empty($_POST['Itemname'])) {
        $itemErr1 = "Please enter a Product Name";
    } else {
        if (isset($_POST['Itemname']) == true && empty($_POST['Itemname']) == false) {
            $itemname = strip_tags($_POST['Itemname']);
                if (is_string($itemname)) {
                    $valid++;
                } else {
                    $itemErr2 = "Please enter a valid Product Name";
                }
        }
    }
    if (empty($_POST['CaseSize'])) {
        $caseErr1 = "Please enter a Case Size";
    } else {
        if (isset($_POST['CaseSize']) == true && empty($_POST['CaseSize']) == false) {
            $case = strip_tags($_POST['CaseSize']);
                if ($case > 0 and $case < 1000) {
                    $valid++;
                } else {
                    $caseErr2 = "Please enter a valid Case Size";
                }
        }
    }
    if (empty($_POST['Company'])) {
        $companyErr1 = "Please enter a Company Name";
    } else {
        if (isset($_POST['Company']) == true && empty($_POST['Company']) == false) {
            $company = strip_tags($_POST['Company']);
                if (is_string($company)) {
                    $valid++;
                } else {
                    $companyErr2 = "Please enter a valid Company Name";
                }
        }
    }
    if (empty($_POST['Category'])) {
        $categoryErr1 = "Please enter a Category";
    } else {
        if (isset($_POST['Category']) == true && empty($_POST['Category']) == false) {
            $category = strip_tags($_POST['Category']);
                if (preg_match("/^[a-zA-Z ]*$/",$category)) {
                    $valid++;
                } else {
                    $categoryErr2 = "Please enter a valid Category";
                }
        }
    }
    if (is_int($_POST['Restrict'])) {
        $restrictErr1 = "This is important";
    } else {
        if (($_POST['Restrict']) == 1 || ($_POST['Restrict']) == 2) {
        $restrict = strip_tags($_POST['Restrict']);
    } else {$restrictErr1 = "That's not an answer.";}}
    if ($valid > 4) {
        $addlib = "INSERT INTO upclib (UPC, ItemName, CaseSize, Company, Category, AddedBy, Restricted) VALUES('$upc','$itemname','$case','$company','$category', '$userId', '$restrict')";
        if (mysqli_query($dbconnect, $addlib)) {
            $newId = mysqli_insert_id($dbconnect);
            $itemId = item.$newId;
                 $dbsuccess = "Success! Item will be added to Library once Verified.<br />View <a href='ViewLibrary.php'>UPC Library</a>";    
        } else {
            $dbErr1 = "Couldn't Add Item to Library.";
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
                        <?php if ($admin == 1){echo "<li><a href='Admin.php'>Admin</a></li>";} ?>
                    </ul>

                </nav>
            </div>
            <div id="Content">
                <div id="PageHeading">
                    <h1><?php echo $username; ?>'s Admin</h1>
                </div>
                <div id="ContentLeft">
                    <h2>Add a new Item</h2><br/>
                    <h4>Every validated UPC you enter<br /> earns you Cred</h4><br/>
                    <h6>Please remember: Being an Admin is a priveledge!</h6>                   
                    <h6>Misuse of this page may result in your Admin status</h6>
                    <h6>being revoked. Only add real UPC codes with the</h6>
                    <h6>most accurate Product information possible.</h6>                   
                </div>
                <div id="ContentRight">
                    <form method="POST" action="NewItem.php">
                    <table border="0">
                        <tbody>
                            <tr><td><span class="error">
                                <?php if ($valid <= 3){echo "*Required Field.";}
                                echo "$dbErr1";?></span><h6><?php echo "$dbsuccess";?></h6></td></tr>
                            <tr>
                            <tr>    
                                <td><br /><h6>UPC Code</h6>
                                    
                                    <input type="text" name="UPC" value="<?php echo "$upc";?>" /><h6><a href="ScanCode.php">Scan</a></h6>
                                </td>
                                <td><br /><br /><span class="error">*<?php echo "$upcErr1"; echo "$upcErr2"; echo "$upcErr3"; echo "$upcErr4";?></span></td>
                             </tr>
                             <tr>
                                <td><br /><h6>Product Name</h6>
                                    <input type="text" name="Itemname" value="<?php echo "$itemname";?>" />
                                </td>
                                <td><span class="error"><br />*<?php echo "$itemErr1"; echo "$itemErr2";?></span></td>
                            </tr>
                             <tr>
                                <td><br /><h6>Case Size</h6>
                                    <input type="text" name="CaseSize" value="<?php echo "$case";?>" />
                                </td>
                                <td><span class="error"><br />*<?php echo "$caseErr1"; echo "$caseErr2";?></span></td>
                            </tr>
                             <tr>
                                <td><br /><h6>Company Name</h6>
                                    <input type="text" name="Company" value="<?php echo "$company";?>" />
                                </td>
                                <td><span class="error"><br />*<?php echo "$companyErr1"; echo "$companyErr2";?></span></td>
                            </tr>
                             <tr>
                                <td><br /><h6>Category</h6>
                                    <select name="Category">
                                        <option value="">Select</option>
                                        <option value="Food">Food</option>
                                        <option value="House">Household</option>
                                        <option value="Health">Health</option>
                                        <option value="Outdoor">Outdoor</option>
                                        <option value="Accessories">Accessories</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </td>
                                <td><span class="error"><br />*<?php echo "$categoryErr1"; echo "$categoryErr2";?></span></td>
                            </tr>
                            <tr>
                                <td>
                                    <br /><h6>Are there any Age Restrictions</h6>
                                    <h6> pertaining to the sale of this item?</h6>
                                </td>
                                <td>
                                    <h6><br /><input type = 'radio' Name ='Restrict' value= '1'
                                <?php print $restrict; ?>
                                >Yes&nbsp;&nbsp;&nbsp;
                            <input type = 'radio' Name ='Restrict' value= '2'
                                <?php print $restict; ?>
                                >No
                                    </h6></td><td><span class='error'><br />*
                                    <?php echo $restrictErr1; ?>
                                    </span></td>
                            </tr>
                            <tr>
                                <td>
                                    <br /><input type="submit" name="submit" value="Enter Item">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
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
