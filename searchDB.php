<?php

require "City.php";


if (isset($_REQUEST["logout"])) {

    // destroy the cookie
    session_start();
    setcookie(session_name(), "", time() - 42000);
    $_SESSION = [];

    session_destroy();
    echo 'index.php';
} else if (isset($_REQUEST['history'])) {
    returnHistory();
} else if (isset($_REQUEST["letter"])) {
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

else if (isset($_REQUEST["city"])) {

    $var = $_REQUEST["city"];
    try {



        $user = 'CS1133611';
        $password ='brestlat';
        $dataSourceName = 'mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1133611';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $empty = false;


        $count = substr_count($var, ',');
        if ($count == 1) { //no province
            $stmt = $pdo->prepare("SELECT countryName , provinceName ,cityName ,population  from cities where cityName = ? and countryName = ?");

            $var1 = explode(",", $var)[0]; //the city name
            $var2 = explode(",", $var)[1]; //the country


            $stmt->bindParam(1, $var1);
            $stmt->bindParam(2, $var2);
        } else if ($count == 2) { //yes province
            $var1 = explode(",", $var)[0]; //the city name
            $var2 = explode(",", $var)[1]; //the province)
            $var3 = explode(",", $var)[2]; //the country name
            $stmt = $pdo->prepare("SELECT countryName , provinceName ,cityName ,population  from cities where cityName = ? and countryName = ? and provincename = ?");

            $stmt->bindParam(1, $var1);
            $stmt->bindParam(2, $var3);
            $stmt->bindParam(3, $var2);
        } else {
            $empty = true;
        }

        if ($empty == false) {


            $stmt->execute();
            $row = $stmt->fetch();
            $city = new City($row["countryName"], $row["provinceName"], $row["cityName"], $row["population"]);

            echo "<table><tr><th>Country Name</th><th>Province Name</th><th>City Name</th><th>Population</th></tr>";
            echo "<tr><td>$city->country</td><td>$city->province</td><td>$city->city</td><td>$city->pop</td></tr>";
            echo "</table>";

            addToHistory($var);
        } else
            echo "No data found";
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}

function findEntries($var) {
    try {


        $user = 'CS1133611';
        $password ='brestlat';
        $dataSourceName = 'mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1133611';
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

function addToHistory($city) {
    session_start();
    $username = $_SESSION['user'];

    try {
        $user = 'CS1133611';
        $password ='brestlat';
        $dataSourceName = 'mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1133611';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT id from Users where user = ?");

        $stmt->bindParam(1, $username);
        $stmt->execute();

        $row = $stmt->fetch();
        $id = $row['id'];








        $stmt = $pdo->prepare("SELECT * from UserHistory where id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        $counter = $row['counter'];

        $history = array($row["search1"], $row["search2"], $row["search3"], $row["search4"]);

        foreach ($history as $var) {
            if ($var == $city) {
                return "";
            }
        }


        if ($counter == 4) {
            $counter = 1;
        } else {
            $counter++;
        }

        $counterPosition = $counter - 1;
        $colums = array("search1", "search2", "search3", "search4");
        $stmt = $pdo->prepare("UPDATE UserHistory set counter =?, $colums[$counterPosition] = ? where id = ?");


        $stmt->bindParam(1, $counter);
        $stmt->bindParam(2, $city);
        $stmt->bindParam(3, $id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        var_dump($e);
    } finally {
        unset($pdo);
    }
}

function returnHistory() {
    session_start();
    $username = $_SESSION['user'];

    try {
        $user = 'CS1133611';
        $password ='brestlat';
        $dataSourceName = 'mysql:host=korra.dawsoncollege.qc.ca;dbname=CS1133611';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id from Users where user = ?");

        $stmt->bindParam(1, $username);
        $stmt->execute();

        $row = $stmt->fetch();
        $id = $row['id'];


        $stmt = $pdo->prepare("SELECT * from UserHistory where id = ?");

        $stmt->bindParam(1, $id);
        $stmt->execute();



        if ($stmt->execute()) {

            $row = $stmt->fetch();

            $first = $row["search1"];
            $second = $row["search2"];
            $third = $row["search3"];
            $fourth = $row["search4"];

            
            if ($first == " " && $second == " " && $third == " " && $fourth == " " ) {
                echo "<h2>No history</h2>";
            } else {

                echo "<select name=temp id=test2 onchange = changeFuncForHistory() size = 4>";
                echo "<option value='' disabled selected style=display:none;></option>";

                echo "<option value = '$first'> $first</option>";
                echo "<option value = '$second' >$second</option>";
                echo "<option value = '$third' >$third</option>";
                echo "<option value = '$fourth' >$fourth</option>";


                echo "</select>";
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}
