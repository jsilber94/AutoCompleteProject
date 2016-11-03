<!DOCTYPE html>
<html>
    <body>


        <form action="" method = "post">
            User name:
            <input type="text" name="username">
            <br><br>
            Password:
            <input type="password" name="password">
            <br><br>
            <input type="hidden" name="action" value="login">
            <input type="submit" value="Login">
        </form>

        <h1> or </h1>

        <form action="" method = "post">
            User name:
            <input type="text" name="username">
            <br><br>
            Password:
            <input type="password" name="password">
            <br><br>
            <input type="hidden" name="action" value="register">

            <input type="submit" value="Register">
        </form>


    </body>      
</html>


<?php
if (isset($_POST['action']) == true && $_POST['action'] == 'register') {

    $user = $_POST['username'];
    $pass = $_POST['password'];
    checkUniqueUsername($user);
    if(checkUniqueUsername($user) == 1)
        echo "Username or Password is invalid. Please try again";
    else{
        createNewUser($user,$pass);   
    }
       
    
} else if (isset($_POST['action']) == true && $_POST['action'] == 'login') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    
}

function checkLoginAndPassword(){
    
    
}

function checkUniqueUsername($var) {

    try {

        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT user from Users where user = ?");

        $stmt->bindParam(1, $var);


        if ($stmt->execute()) {

            $row = $stmt->fetch();
            if ($row != null)
                return true;
            else
                return false;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}

function createNewUser($username,$pass){
    try {

        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("insert into Users (user,pass) Values(?,?)");

        $stmt->bindValue(1, $username);
        $stmt->bindValue(2, $pass);
    


        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
    
}