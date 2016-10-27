<?php


if (isset($_REQUEST["letter"])) {
    $letter = $_REQUEST["letter"];
    
    if ($letter !== "") {
        $entries = findEntries($letter);
        
        $size = count($entries); 
        
        echo "<form action='demo_form.asp'>"
        . "<select name=cities size = $size>";

        foreach ($entries as $value) {
            
            $temp = $value->city;
            var_dump($temp);
            echo "<option value=$temp onclick=changeFunc(value);>$temp</option>";
        }
        echo "</select>
           
            </form>";
        
       
    }

}

function findEntries($var) {
    try {
        
        require "City.php";
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