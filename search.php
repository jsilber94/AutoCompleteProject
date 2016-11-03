<!DOCTYPE html>
<html>
    <head>

        <script>

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

            function changeFunc() {
                
                var value = document.getElementById("test").value;
              document.getElementById("cityName").value = value;
       

            }
            
            function enter(value){
                
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
            
            function printHistory(){
                 var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        
                        
                        document.getElementById("history").innerHTML = this.responseText;
                    }
                };

                xmlhttp.open("GET", "searchDB.php?history=return", true);
                xmlhttp.send();
                
            }
            window.onload= printHistory;
        </script>
    </head>



</script>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>
    

    
    Your last five searches<br><br>
    <div id ="history"></div>
    Please enter City Name <br><br>
    <input id = "cityName" type="text" name="cityname" onkeydown="keyPress(event)">

    <div id="txtHint"></div>


    <input type=submit value=Submit onmousedown="enter()">
   

    <div id ="output"></div>
    
    
</body>
</html>



