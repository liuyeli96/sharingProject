<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=cs2102")
or die('Could not connect: HERE' . pg_last_error());

$q = $_GET["q"];

if (strlen($q)>0) {

  $hint="";


  $query = 'select distinct o.category, o.itemname, o.description, o.price, o.owner, a.auctionid
  from object o, auction a where o.availability=TRUE and a.objectid = o.productid';
  $result = pg_query($query) or die('Query failed: ' . pg_last_error());

  while ($row = pg_fetch_row($result)){

      if(stristr($row[1], $q)){
          if($hint==""){
              $hint = "<a href='browsing.php#id".$row[5]."'><u>".
              $row[0]."</u> <br/> &nbsp;".$row[1].": &nbsp; $".$row[3]."</a><hr>";
          } else {
              $hint = $hint . "<a href='browsing.php#id".$row[5]."'><u>".
              $row[0]."</u> <br/> &nbsp;".$row[1].": &nbsp; $".$row[3]."</a><hr>";
          }
      }
  }
}

if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

//output the response
echo $response;
 ?>
