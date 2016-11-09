<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=10220911")
or die('Could not connect: HERE' . pg_last_error());
ob_start();
session_start();
if(!isset($_SESSION['user'])){
    echo "Please login <a href='FirstPage.php'>here</a>.";
    header("Location: /Login.php");
    exit();
}
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
        $currObjectID = round(crc32($_POST['category'].$_POST['itemName'].$_POST['description']) / 15);
        if($fileUploaded){
            $target_file = $currObjectID . ".jpg";
        } else {
            $target_file = "default.jpg";
            $uploadOK = 0;
        }
        $date = date('Y-m-d');
        $query = "INSERT INTO object VALUES('".$currObjectID."',
        '".$_POST['category']."','".$_POST['itemName']."','".$_POST['description']."',
        '".$_POST['price']."','".$date."','TRUE','".$target_file."','".$_SESSION['user']."')";
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
