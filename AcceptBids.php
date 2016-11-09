<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Accepting bids</title>
        <link href="styles.css" media="all" rel="Stylesheet" type="text/css"/>

    </head>
    <body>

        <?php
        
        $counter_name = "counter.txt";
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
            fclose($f); 
        }
        
        
        
        $dbconn = pg_connect("host=localhost port=5432 dbname=Sharing user=postgres password=19960116")
            or die('Could not connect: ' . pg_last_error());
        ?>
        
        <div class="sect1">
            <h1>Find the bid you want to accept!</h1>
        </div>

        <div class="sect2">

            <ul>


            <?php 
                session_start();
                $user = $_SESSION['user'];
                                           
                
                $query = "SELECT o.itemName, b.price, m.username, b.memberEmail, a.auctionID, o.productID
                            FROM bid b, member m, object o, auction a
                            WHERE b.memberEmail = m.email AND b.auctionID = a.auctionID
                            AND a.owner = o.owner AND o.owner = '".$user."' AND a.objectID = o.productID AND o.availability = 'TRUE'
                            ORDER BY o.itemName ASC, b.price DESC;
                            ";

            $result = pg_query($query) or die('Query failed: ' . pg_last_error());

            while ($row = pg_fetch_row($result)){
                echo "<hr></hr>";
                echo "<div><li><b>Your item name: ".$row[0]."</b><br>The bid value: ".$row[1]."<a href=\"#id".$row[2]."\">

                </li></div>";

                echo "<div id=\"id".$row[5]."\" class=\"modal\">
                    <div><a href=\"#\" title=\"Close\" class=\"close\">X</a>
                    ".$row[1].": &nbsp;".$row[2]." <br><br> <b>Bidder information:</b><br>Bidder name: ".$row[2]."<br> Bidder e-mail: ".$row[3]."<br>
                    
                    <form>
                        <input id=\"auctionID\" name=\"auctionID\" type=\"hidden\" value=\"".$row[4]."\"></input>
                        <input id = \"bidPrice\" name = \"bidPrice\" type=\"hidden\" value=\"".$row[1]."\" id = \"bidPrice\"> &nbsp;</input>
                        <input id=\"owner\" name=\"owner\" type=\"hidden\" value=\"".$user."\"></input>
                        <input id=\"borrower\" name=\"borrower\" type=\"hidden\" value=\"".$row[3]."\"></input>
                        <input id=\"borrower\" name=\"productID\" type=\"hidden\" value=\"".$row[5]."\"></input>
                        <input type=\"submit\" name=\"acceptBid\"  value=\"Accept this bid!\">
                    </form>
                    </div>
                </div>";
            }
            
                echo "<br>$counterVal<br>";
            if(isset($_GET['acceptBid'])){
                echo "<p></p><p>auction ID : ".$_GET['auctionID']." is now finished! With the winner ".$_GET['borrower']." and at a price of ".$_GET['bidPrice'].".</p>";
                
                
                
                
                
                $date = date('Y-m-d', time());
                echo "$date";
                echo "<br>$date+30<br>";
                
                echo "<br>".$_GET['owner']."<br>";
                echo "<br>".$_GET['borrower']."<br>";
                echo "<br>".$_GET['productID']."<br>";
                echo "<br>$date<br>";
                
                echo "<br>You are visitor number $counterVal to this site<br>";
                
                $Query = "INSERT INTO loan values('".$counterVal."', '".$date."', '2017-11-11', '".$_GET['owner']."', '".$_GET['borrower']."','".$_GET['productID']."');
                UPDATE object	
                SET availability = 'FALSE'
                WHERE productID = '".$_GET['productID']."';
                UPDATE auction
                SET winner = '".$_GET['borrower']."'
                WHERE auctionID = '".$_GET['auctionID']."';";
                

                $Result = pg_query($Query) or die('query failed: '. pg_last_error());
                /*if(!$Result){
                    echo "we failed";
                } else {
                    header("Location:welcome.php");
                    exit;
                }*/
            }    
 
            ?>
            </ul>

        </div>
        
        <!--div class="sect4">
            List of Loans:<br>
            <!?php $query = 'SELECT * FROM loan';

            $result = pg_query($query) or die ('Query failed fml '. pg_last_error());
            while($row = pg_fetch_row($result)){
                echo "ItemID: ".$row[0]."&nbsp; Buyer:".$row[1]."&nbsp; Seller:".$row[2]."&nbsp;".$row[3]."&nbsp;".$row[4]."<br>";
            }
            ?>
            
        <div class="sect5">
            List of Bids:<br>
            <!?php $query = 'SELECT * FROM bid';

            $result = pg_query($query) or die ('Query failed fml '. pg_last_error());
            while($row = pg_fetch_row($result)){
                echo "ItemID: ".$row[0]."&nbsp; Buyer:".$row[1]."&nbsp; Seller:".$row[2]."&nbsp;".$row[3]."&nbsp;".$row[4]."<br>";
            }
            ?>    

        </div>
        
        <div class="sect5">
            List of auctions with a winner:<br>
            <!?php $query = 'SELECT * FROM auction WHERE winner IS NOT NULL';

            $result = pg_query($query) or die ('Query failed fml '. pg_last_error());
            while($row = pg_fetch_row($result)){
                echo "AuctionID: ".$row[0]."&nbsp; Deadline: ".$row[1]."&nbsp; Winner: ".$row[2]."&nbsp; ObjectID: ".$row[3]."&nbsp;Owner: ".$row[4]."<br>";
            }
            ?>    

        </div-->    
        <script src="./jquery-2.1.3.min/index.js"></script>

    </body>
</html>
