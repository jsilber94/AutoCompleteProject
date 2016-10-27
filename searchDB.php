<?php

require "City.php";
if (isset($_REQUEST["letter"])) {
    $letter = $_REQUEST["letter"];

    if ($letter !== "") {
        $entries = findEntries($letter);

        $size = count($entries);


        echo "<form action='demo_form.asp'>"
        . "<select name=cities size = $size>";

        foreach ($entries as $value) {

            $temp = $value->city;

            $temp = str_replace(" ", "&nbsp;", $temp);


            echo "<option value= $temp onclick=changeFunc(value);>$temp</option>";
        }
        echo "</select>
           
            </form>";
    }
}

if (isset($_REQUEST["city"])) {

    $var = $_REQUEST["city"];
    try {

        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT countryName,provinceName,cityName,population from cities where cityName=?");

        $stmt->bindParam(1, $var);


        $stmt->execute();
        $row = $stmt->fetch();
        $city = new City($row["countryName"], $row["provinceName"], $row["cityName"], $row["population"]);

        echo "<table><tr><th>Country Name</th><th>Province Name</th><th>City Name</th><th>Population</th></tr>";
        echo "<tr><td>$city->country</td><td>$city->province</td><td>$city->city</td><td>$city->pop</td></tr>";
        echo "</table>";
  
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}

function findEntries($var) {
    try {


        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT countryName,provinceName,cityName,population from cities where cityName like ? limit 5");

        $var = $var . "%";
        $stmt->bindParam(1, $var);



        $cities = [];
        if ($stmt->execute()) {
            $i = 0;

            while ($row = $stmt->fetch()) {

                $cities[$i] = new City($row["countryName"], $row["provinceName"], $row["cityName"], $row["population"]);
                $i++;
            }
        }

        return $cities;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}
