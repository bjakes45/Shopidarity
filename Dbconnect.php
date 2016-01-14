<?php
$dbconnect = mysqli_connect("localhost", "root", "", "users");

if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_error();
}
$checklogin = "CREATE TABLE IF NOT EXISTS `login` (`id` INT(12) NOT NULL AUTO_INCREMENT, `joined` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `email` VARCHAR(50), `username` VARCHAR(30), `password` VARCHAR(100), `country` VARCHAR(50), `location` VARCHAR(50), `firstname` VARCHAR(30), `lastname` VARCHAR(30), `admin` TINYINT(1) NOT NULL, `TotalCred` INT(12) NOT NULL, `SpentCred` INT(12) NOT NULL, AvRate INT(2) NOT NULL, AdAvRate INT(2) NOT NULL, PRIMARY KEY(`id`))";
$mlogin = mysqli_query($dbconnect, $checklogin);
$checklib = "CREATE TABLE IF NOT EXISTS `upclib` (`ID` INT(12) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `UPC` BIGINT(20), `ItemName` LONGTEXT, `CaseSize` INT(8), `Company` LONGTEXT, `ManuId` Int(12), `Category` VARCHAR(20), `AddedBy` INT(12), `Verified` TINYINT(1) NOT NULL, `AvRate` DECIMAL(4,2) DEFAULT '5', PRIMARY KEY(`ID`))";
$mlib = mysqli_query($dbconnect, $checklib);
$checkreq = "CREATE TABLE IF NOT EXISTS `reqadmin` (`id` INT(12) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `userId` INT(12), `a1` INT(10), `a2` INT(10), `a3` INT(10), `delete` TINYINT(1) NOT NULL, PRIMARY KEY(`id`))";
$mreq = mysqli_query($dbconnect, $checkreq);
$checkmanu = "CREATE TABLE IF NOT EXISTS `manufacturer` (`ID` INT(12) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `Name` VARCHAR(30), `AdminID` INT(12), `MgrId` INT(12), `AvRate` DECIMAL(4,2) DEFAULT '5', PRIMARY KEY(`ID`))";
$mmanu = mysqli_query($dbconnect, $checkmanu);
$checkvend = "CREATE TABLE IF NOT EXISTS `vendor` (`ID` INT(12) NOT NULL AUTO_INCREMENT, `Time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `VendName` VARCHAR(30),`Location VARCHAR(30), `AdminID` INT(12), `MgrId` INT(12),`Confirm` TINYINT(1) NOT NULL, `AvRate` DECIMAL(4,2) DEFAULT '5', PRIMARY KEY(`ID`))";
$mvend = mysqli_query($dbconnect, $checkvend);


$checkmgmt = "CREATE TABLE IF NOT EXISTS `mgmt` (`id` INT(12) NOT NULL AUTO_INCREMENT, `userId` VARCHAR(20), `mgcode` INT(10) NOT NULL, PRIMARY KEY(`ID`))";
$mmgmt = mysqli_query($dbconnect, $checkmgmt);
$checkcountry = "CREATE TABLE IF NOT EXISTS `country` (`ID` INT(12) NOT NULL AUTO_INCREMENT, `Country` VARCHAR(20), `Currency` VARCHAR(6), `Partition` VARCHAR(30), PRIMARY KEY(`ID`))";
$mcountry = mysqli_query($dbconnect, $checkcountry);
