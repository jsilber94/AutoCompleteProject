<?php

require "City.php";
if (isset($_REQUEST["letter"])) {
    $letter = $_REQUEST["letter"];

    if ($letter !== "") {
        $entries = findEntries($letter);

        $size = count($entries);


        echo "<form action='demo_form.asp'>"
        . "<select name=cities id=test onchange = changeFunc() size = $size>";
        echo "<option value='' disabled selected style=display:none;></option>";
        foreach ($entries as $value) {

            if ($value->province == "")
                $temp = $value->city . "," . $value->country;
            else
                $temp = $value->city . "," . $value->province . "," . $value->country;

            //$temp = str_replace(" ", "&nbsp;", $temp);
            //echo "<option value= $temp onclick=changeFunc(value);>$temp</option>";

            echo "<option value= '$temp' >$temp</option>";
        }
        echo "</select>
           
            </form>";
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

if (isset($_REQUEST["city"])) {

    $var = $_REQUEST["city"];
    try {



        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $count = substr_count($var, ',');
        if ($count < 2) { //no province
            $stmt = $pdo->prepare("SELECT countryName , provinceName ,cityName ,population  from cities where cityName = ? and countryName = ?");

            $var1 = explode(",", $var)[0]; //the city name
            $var2 = explode(",", $var)[1]; //the country
            

            $stmt->bindParam(1, $var1);
            $stmt->bindParam(2, $var2);
        } else { //ys prvince
            $var1 = explode(",", $var)[0]; //the city name
            $var2 = explode(",", $var)[1]; //the province)
            $var3 = explode(",", $var)[2]; //the country name
            $stmt = $pdo->prepare("SELECT countryName , provinceName ,cityName ,population  from cities where cityName = ? and countryName = ? and provincename = ?");

            $stmt->bindParam(1, $var1);
            $stmt->bindParam(2, $var3);
            $stmt->bindParam(3, $var2);
        }




        var_dump($var1);
        var_dump($var2);



        // $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'City');
        $stmt->execute();


        // $city = $stmt->fetchAll();

        $row = $stmt->fetch();
        $city = new City($row["countryName"], $row["provinceName"], $row["cityName"], $row["population"]);
        var_dump($city);
        echo "<table><tr><th>Country Name</th><th>Province Name</th><th>City Name</th><th>Population</th></tr>";
        echo "<tr><td>$city->country</td><td>$city->province</td><td>$city->city</td><td>$city->pop</td></tr>";
        echo "</table>";
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}
