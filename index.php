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

            function changeFunc(value) {
                alert(value);

            }
        </script>
    </head>



</script>

</head>
<body>

    Please enter City Name <br><br>
    <input id = "cityName" type="text" name="cityname" onkeydown="keyPress(event)">

    <div id="txtHint"></div>

    <form>
        <input type=submit value=Submit>
    </form>

</body>
</html>



