<!DOCTYPE html>
<html>
    <head>
        <script>
            //based on the letter being typed
            function keyPress(e) {
                if (e.keyCode === 8) {
                    var letter = document.getElementById('cityName').value;
                    letter = letter.substring(0, letter.length - 1);
                } else
                {
                    var letter = document.getElementById('cityName').value;
                    letter += String.fromCharCode(e.keyCode);
                }

                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };

                xmlhttp.open("GET", "searchDB.php?letter=" + letter, true);
                xmlhttp.send();

            }
            /**
             * 
             * Fills in the text box from the user typing
             */
            function changeFunc() {

                var value = document.getElementById("test").value;

                document.getElementById("cityName").value = value;

            }
            /*
             * 
             * Fills in the text box from the history
             */
            function changeFuncForHistory() {

                var value = document.getElementById("test2").value;

                document.getElementById("cityName").value = value;

            }
            /*
             * When the user presses the submit button
             */
            function enter(value) {

                var city = document.getElementById("cityName").value;

                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {


                        document.getElementById("output").innerHTML = this.responseText;
                    }
                };

                xmlhttp.open("GET", "searchDB.php?city=" + city, true);
                xmlhttp.send();
            }
            /*
             * Prints the users history
             */
            function printHistory() {
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {

                        document.getElementById("history").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "searchDB.php?history=return", true);
                xmlhttp.send();

            }
            /*
             * Closes down the session
             */
            function logout() {
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {

                        var link = this.responseText;
                        //alert(link);
                        window.location.href = link;
                    }
                };

                xmlhttp.open("GET", "searchDB.php?logout=logout", true);
                xmlhttp.send();
            }
            window.onload = printHistory;
        </script>
    </head>


</script>
<style> 
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        color:white
    }
    body {
        background-image: url("background.jpg");
    }
    h2{
        color:white;
    }
    #right{
        text-align: right;
    }
    html{
        text-align: center;
    }
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        color:black;
        margin:0 auto;
    }

</style>
</head>

<body>
    <div id ='right'>
        <input id ="logout" type="submit" value ="Logout" onmousedown="logout()" class = logout>
    </div>
    <br><br>

    <h2>Your last five searches</h2><br><br>
    <div id ="history"></div>
    <h2>Please enter City Name</h2> <br><br>
    <input id = "cityName" type="text" name="cityname" onkeydown="keyPress(event)">

    <div id="txtHint"></div>


    <input type=submit value=Submit onmousedown="enter()">


    <div id ="output"></div>


</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
} else { //if they do
    session_regenerate_id();
}
