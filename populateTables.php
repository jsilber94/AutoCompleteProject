<?php

setUpTable();
readTheFile();

function readTheFile() {
    $file = 'cities.txt';

    $handle = fopen($file, "r");


    while (!feof($handle)) {

        $entry = fgets($handle);

        $count = substr_count($entry, ",");

        if ($count == 2) {
            $pop = trim(explode(";", $entry)[0]);
            $city = trim(explode(";", explode(",", $entry)[0])[1]);
            $province = trim(explode(",", $entry)[1]);
            $country = trim(explode(",", $entry)[2]);

            insertIntoTable($pop, $city, $province, $country);
        } else {

            $pop = trim(explode(";", $entry)[0]);
            $city = trim(explode(";", explode(",", $entry)[0])[1]);
            $country = trim(explode(",", $entry)[1]);


            insertIntoTable($pop, $city, "", $country);
        }
    }
}

function insertIntoTable($pop, $city, $province = "", $country) {

    try {
        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO cities(countryName,provinceName,cityName,population) Values(?,?,?,?)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $country);
        $stmt->bindParam(2, $province);
        $stmt->bindParam(3, $city);
        $stmt->bindParam(4, $pop);


        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}

function setUpTable() {

    try {
        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost';
        $pdo = new PDO($dataSourceName, $user, $password);


        $sql = "CREATE database cities";
        $pdo->exec($sql);
        echo 'Database Created';

        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);


        $sql = "drop table if exists cities";
        $pdo->exec($sql);
        echo 'Table dropped';


        $sql = 'CREATE TABLE cities(
                id int not null auto_increment primary key,
                countryName varchar(255) not null,
                provinceName varchar(255) not null,
                cityName varchar(255) not null,
                population INT(7) not null)';
        $pdo->exec($sql);
        
        echo 'Cities Table Created';
        
            $sql = 'CREATE TABLE Users(
                id int not null auto_increment primary key,
                user varchar(255) not null,
                pass varchar(255) not null,
                counter int(1) default 0)';
        $pdo->exec($sql);
        echo 'Users Table Created';
        
        
        $sql = 'CREATE TABLE UserHistory(
            id int not null,
            search1 varchar(255) not null,
            search2 varchar(255) not null,
            search3 varchar(255) not null,
            search4 varchar(255) not null,            
            PRIMARY KEY (id),
            FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE)';
        $pdo->exec($sql);
        echo 'UserHistory Table Created';
        
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}
