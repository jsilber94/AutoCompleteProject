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
    if (checkUniqueUsername($user) == 1){
        echo "Username or Password is invalid. Please try again";
    }
    else {
        createNewUser($user, $pass);
    }
} else if (isset($_POST['action']) == true && $_POST['action'] == 'login') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $temp = checkLoginAndPassword($user, $pass);

    if ($temp == "logged in") {
        //logged in so create session

        session_start();
        session_regenerate_id();
        $_SESSION['user'] = $user;
        $_SESSION['time'] = time();   
        header('Location: search.php');
        
        
        
    } else
        echo $temp;
}

function checkLoginAndPassword($username, $pass) {
    try {

        $user = 'homestead';
        $password = 'secret';
        $dataSourceName = 'mysql:host=localhost;dbname=cities';
        $pdo = new PDO($dataSourceName, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT counter from Users where user = ?");
        $stmt->bindParam(1, $username);
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            if ($row != null) {
                if ($row['counter'] == 3)
                    return "User has execced the number of attempts";
                else
                    $counter = $row['counter'];
            } else
                $counter = -1;
        } else
            $counter = -1;

        //check counter


        $stmt = $pdo->prepare("SELECT id,counter from Users where user = ? and pass = ?");

        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $pass);

        if ($stmt->execute()) {

            $row = $stmt->fetch();
            if ($row != null) {

                return "logged in";
            } else {
                $stmt = $pdo->prepare("UPDATE Users set counter = ? where user = ?");
                $stmt->bindValue(1, $counter + 1);
                $stmt->bindValue(2, $username);
                $stmt->execute();


                return "Username or Password is invalid. Please try again";
                
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
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

function createNewUser($username, $pass) {
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

        $stmt = $pdo->prepare("Select id from Users where user = ?");
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $row = $stmt->fetch();
        $id = $row['id'];

     
        $stmt = $pdo->prepare("insert into UserHistory (id,search1,search2,search3,search4) Values(?,?,?,?,?)");
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, " ");
        $stmt->bindValue(3, " ");
        $stmt->bindValue(4, " ");
        $stmt->bindValue(5, " ");
        
        
        
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } finally {
        unset($pdo);
    }
}
