<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sharing Project</title>
        <link href="styles.css" media="all" rel="Stylesheet" type="text/css"/>

        <script>
        function showResult(str) {
          if (str.length==0) {
            document.getElementById("searchbar").innerHTML="";
            document.getElementById("searchbar").style.border="0px";
            return;
          }
          if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
          } else {  // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
              document.getElementById("searchbar").innerHTML=this.responseText;
              document.getElementById("searchbar").style.border="1px solid #A5ACB2";
            }
          }
          xmlhttp.open("GET","search.php?q="+str,true);
          xmlhttp.send();
        }
        </script>
    </head>
    <body>

        <?php
        $dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=12345678")
            or die('Could not connect: ' . pg_last_error());
        //$currObjectID = 20;
              session_start();
      $user = $_SESSION['user'];
       if ( isset($_SESSION['user'])=="" ) {
            header("Location: FirstPage.php");
            exit;
        }
        ?>

        <div class="sect1">
            <h1>Find something to buy!</h1>
        </div>


        <div class="sect2">
            <a href="stuffSharingObject.php"><button type="button">Add Item</button></a><br>
            <form>
                <input type="text" name="searchbar" placeholder="What do you want?" onkeyup="showResult(this.value)"/>
                <div id="searchbar"></div>
            </form>

            <!--search bar and other crap included here -->
        </div>

        <div class="sect3">

            <ul>


            <?php $query = 'SELECT distinct o.category, o.itemname, o.description, o.price, o.owner, a.auctionid
            from object o, auction a where o.availability=TRUE and a.objectid = o.productid';


            //'SELECT distinct o.category, o.itemname, o.description, o.price, o.owner, a.auctionid, b.price
            //FROM object o, auction a, bid b group by o.itemname';
            // -- WHERE a.objectid = o.productid AND b.auctionid = a.auctionid AND a.objectid = o.productid
            // -- AND b.price >=ALL (SELECT bi.price from bid bi WHERE bi.auctionid = a.auctionid)
            // -- UNION
            // -- SELECT o.category, o.itemname, o.description, o.price, o.owner, a.auctionid, b.price
            // -- FROM object o, auction a, bid b
            // -- WHERE a.objectid = o.productid AND b.auctionid = a.auctionid AND a.objectid = o.productid';

//this needs to be changed in order to show the maximum bid price at the moment instead of just price.
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());

            while ($row = pg_fetch_row($result)){
                echo "<hr></hr>";
                echo "<div><li>".$row[0].":&nbsp; &nbsp; ".$row[1]."<a href=\"#id".$row[5]."\"><button type=\"button\">Go to Item</button></a>

                </li></div>";
                $price = $row[3] > $row[6] ? $row[3] : $row[6];
                echo "<div id=\"id".$row[5]."\" class=\"modal\">
                    <div><a href=\"#\" title=\"Close\" class=\"close\">X</a>
                    ".$row[1].": &nbsp;".$row[2]." <br> Current Bid: $".$price."<br>
                    <img src=\"img/884856436.jpg\" width=\"100px\" height=\"100px\"/>
                    <form>
                        <input id=\"auctionID\" name=\"auctionID\" type=\"hidden\" value=\"".$row[5]."\"></input>
                        <input type=\"text\" name = \"bidPrice\" id = \"bidPrice\"> &nbsp; <input type=\"submit\" name=\"bidSubmit\" value=\"Bid for it!\">
                    </form>
                    </div>
                </div>";

            }
            if(isset($_GET['bidSubmit'])){
                //echo "auction ID : ".$_GET['auctionID']."";

                $insertQuery = "INSERT INTO bid values('".$_GET['bidPrice']."', '".$_SESSION['user']."', '".$_GET['auctionID']."');";

                $insertResult = pg_query($insertQuery) or die('query fucked up: '. pg_last_error());
                if(!insertResult){
                    echo "we dun fucked up";
                } else {
                    header("Location:browsing.php");
                    exit;
                }
            }
            ?>
            </ul>

        </div>
        <div class="sect4">
    <!--        List of Loans:<br> -->
            <?php
            // $query = 'SELECT * FROM loan';
            //
            // $result = pg_query($query) or die ('Query failed fml '. pg_last_error());
            // while($row = pg_fetch_row($result)){
            //     echo "ItemID: ".$row[0]."&nbsp; Buyer:".$row[1]."&nbsp; Seller:".$row[2]."&nbsp;".$row[3]."&nbsp;".$row[4]."<br>";
            // }
            ?>

        </div>

          <a href="AccountPage.php"><button>Go to Account Page</button></a><br>


        <div class="copyright">
            Copyright &#169; VYMMS
        </div>


        <script src="./jquery-2.1.3.min/index.js"></script>

    </body>
</html>
