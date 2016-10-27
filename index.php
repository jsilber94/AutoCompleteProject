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
            <input type="submit" value="Submit">
        </form>



    </body>      
</html>


<?php

if(isset($_POST['username']) && isset($_POST['password'])){
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //check if username exists -> if no error
    //check if password is right -> if not error
                               //-> if yes new page 
    
    
    
}
