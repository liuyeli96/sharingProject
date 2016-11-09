<html>
<head> 
    <title>WELCOME!</title> 
    <link href="styles.css" media="all" rel="Stylesheet" type="text/css"/>
</head>

<body>

    <div class="sect1">
        <h1>Login</h1>
    </div>


    <?php
    $dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres
        password=10220911")
    or die('Could not connect: ' . pg_last_error());
    ?>

    <?php
    ob_start();
    session_start();

    if ( isset($_SESSION['user'])!="" ) {
        header("Location: AccountPage.php");
        exit;
    }
    ?>


<form>
    <div style="margin-left:10px">

	<label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Email: </span><input style="float:right; display:inline" type="text" name="Email" id="Email"></label> <br>

    <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Password: </span><input style="float:right; display:inline" type="password" name="Password" id="Password"></label> <br>

    <input type="submit" name="formSubmit" value="Login" >
    </div>

</form>


<?php

if(isset($_GET['formSubmit']))
{
    $query = "SELECT email, password FROM member WHERE email = '".$_GET['Email']."' AND password = '".$_GET['Password']."'";

    echo "<b>SQL:   </b>".$query."<br><br>";
    $result = pg_query($query) or die('Search failed: ' . pg_last_error());


    if (pg_num_rows($result) == 1) {
    	echo "true";
        $_SESSION['user'] = $_GET['Email'];
        header("Location:AccountPage.php");
    	//Change page
        exit;
    } else {
    	echo "Wrong Password!";
    }

    pg_free_result($result);
}
?>

</td> </tr>
<?php
pg_close($dbconn);
?>

<p style="clear:both">Copyright &#169; VYMMS</p>

</body>
</html>
