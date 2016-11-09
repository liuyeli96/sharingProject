<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=19960116")
or die('Could not connect: HERE' . pg_last_error());
ob_start();
session_start();
if(!isset($_SESSION['user'])){
    echo "Please login <a href='FirstPage.php'>here</a>.";
    header("Location: /Login.php");
    exit();
}
$user = $_SESSION['user'];
$target_dir = "img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["formSubmit"])) {
    
    $fileUploaded = is_uploaded_file($_FILES["fileToUpload"]["tmp_name"]);
    echo $fileUploaded. "\n";
        
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false || !is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
        
        $counter_name1 = "counter1.txt";
        
        // Counter1 is the ID for object
        // Check if a text file exists. If not create one and initialize it to zero.
        if (!file_exists($counter_name1)) {
            $f = fopen($counter_name1, "w");
            fwrite($f,"0");
            fclose($f);
        }    
        // Read the current value of our counter file and add 1
        $f = fopen($counter_name1,"r");
        $counterVal1 = fread($f, filesize($counter_name1));
        fclose($f);
        $counterVal1++;
        $f = fopen($counter_name1, "w");
        fwrite($f, $counterVal1);
        fclose($f);
        echo "<br>You added object with number $counterVal1 to the database<br>";
        
        $counter_name2 = "counter2.txt";
        
        // Counter2 is the ID for auction
        // Check if a text file exists. If not create one and initialize it to zero.
        if (!file_exists($counter_name2)) {
            $f = fopen($counter_name2, "w");
            fwrite($f,"0");
            fclose($f);
        }    
        // Read the current value of our counter file and add 1
        $f = fopen($counter_name2,"r");
        $counterVal2 = fread($f, filesize($counter_name2));
        fclose($f);
        $counterVal2++;
        $f = fopen($counter_name2, "w");
        fwrite($f, $counterVal2);
        fclose($f);
        echo "<br>You added auction with number $counterVal2 to the database<br>";
        
        
        if($fileUploaded){
            $target_file = $counter_name1 . ".jpg";
        } else {
            $target_file = "default.jpg";
            $uploadOK = 0;
        }
        
        $date = date('Y-m-d', time());
        $deadlinedate = strtotime("+1 day", strtotime($date));
	    $deadlinedate = date("Y-m-d", $deadlinedate);
        
        $query = "INSERT INTO object VALUES('".$counterVal1."', '".$_POST['category']."','".$_POST['itemName']."','".$_POST['description']."','".$_POST['price']."','".$date."', TRUE, '".$user."');
        
        
        INSERT INTO auction VALUES('".$counterVal2."', '".$deadlinedate."', NULL,'".$counterVal1."', '".$user."');
        
        INSERT INTO bid VALUES('".$_POST['price']."', '".$user."', '".$counterVal2."');";
        
//echo "<b>SQL:   </b>".$query."<br><br>";
        echo $query . "\n";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        echo $currObjectID;
        if(!result){
            echo "Please enter all fields";
            $uploadOk = 0;
        }
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if(!$fileUploaded || ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG")){
    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
$uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    header("Location: browsing.php");
    exit;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        header("Location: browsing.php");
        exit;
    } else {
        echo "Sorry, there was an error uploading your file.";
        header("Location: browsing.php");
        exit;
    }
}
echo "what the fuck7";
?>
