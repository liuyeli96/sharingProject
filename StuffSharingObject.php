<html>
<head> 
    <title>Stuff Sharing Catalog</title> 
    <link href="styleAddItem.css" media="all" rel="Stylesheet" type="text/css"/>
</head>

<body>
   
    <table >
   
   
         <div class="sect1">
            <h1>Add Your Item!</h1>
            </div>
        <?php
        ob_start(); 
        session_start(); 
        
        $dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=10220911")
        or die('Could not connect: HERE' . pg_last_error());
         if ( isset($_SESSION['user'])=="" ) {
            header("Location: FirstPage.php");
            exit;
        }
        
       /* $counter_name = "counterProductID.txt";
        // Check if a text file exists. If not create one and initialize it to zero.
        if (!file_exists($counter_name)) {
            $f = fopen($counter_name, "w");
            fwrite($f,"0");
            fclose($f);
        }
        
        // Read the current value of our counter file
        $f = fopen($counter_name,"r");
        $counterVal = fread($f, filesize($counter_name));
        fclose($f);
        // Has visitor been counted in this session?
        // If not, increase counter value by one
        if(!isset($_SESSION['hasVisited'])){
            $_SESSION['hasVisited']="yes";
            $counterVal++;
            $f = fopen($counter_name, "w");
            fwrite($f, $counterVal);
            fclose($f); */
        ?>

        
            <div class="sect3">
            
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="sect3">
                        <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Category: </span><input style="float:right; display:inline" type="text" name="category" id="category"></label> <br>
                    </div>
                    <div class="sect3">
                      <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Item Name:</span> <input  style="float:right; display:inline" type="text" name="itemName" id="itemName"></label><br/>
                    </div>
                    <div class="sect3">
                         <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Description:</span> <input style="float:right; display:inline" type="text" name="description" id="description"></label><br/>
                    </div>
                    <div class="sect3">
                      <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Price:</span> <input style="float:right; display:inline" type="text" name="price" id="price"></label><br/>
                    </div>
                    <div class="sect3">
                      <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Location:</span> <input style="float:right; display:inline" type="text" name="location" id="location"> </label><br/>
                    </div>
                    <!--
                    <div class="sect3">
                      <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Available Date:</span> <input style="float:right; display:inline" type="text" name="availableDate" id="availableDate"> </label><br/>
                    </div>
                    
                    <div class="sect3">
                      <label style="color:#2D2D2D; width:250px; display: block"><span style="position:relative;">Availability:</span> <input style="float:right; display:inline" type="text" name="availability" id="availability"></label> <br/>
                    </div>
                    -->
                    <div class="sect3">
                     <input type="file" name="fileToUpload" id="fileToUpload"><br>
                    </div>
                    <input type="submit" name="formSubmit" value="Add Item!" ><br>
                </form>
                <?php
                if(isset($_GET['formSubmit']))
                {   
                    $date = date('Y-m-d');
                    $_SESSION['productID'] = $_SESSION['productID'] + 1; 
                    $query = "INSERT INTO object VALUES('".$_GET['category']."','".$_GET['itemName']."','".$_GET['description']."','".$_GET['price']."','".$_GET['location']."','".$date."','".$_GET['availability']."', '".$_SESSION['user']."')";
    //echo "<b>SQL:   </b>".$query."<br><br>";
                    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
                    if(!result){
                        echo "Please enter all fields";
                    }
                    else{
                        header("Location:welcome.php");
                        exit;
                    }
                }
                ?>

            <?php
            pg_close($dbconn);
            ?>
            
            </table>
             <a href="browsing.php"><button>Go to Browsing Page</button></a><br>
            
                <p>Copyright &#169; VYMMS
                </p> 
        </body>
        </html>
