<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        td{
            padding: 10px;
        }
        .topRow{
            border-top: 1pt solid black;
        }
    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<?php include "header.php"; ?>

<?php

//session_start();
$username = $_SESSION['username'];
$customerID = $_SESSION['customerID'];
$reservationArr = unserialize($_GET['reservationArr']);

//connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "1234";
$db = "cinema_theatre";


//Ticket info
$movieTitle = "";
$reserveDate = "";
$reserveTime = "";
$seatRow = 0;
$seatNumber = 0;
$ticketPrice = 0;

//foreach($reservationArr as $id){
//    echo $id."<br>";
//}
?>
<div class="content" id="ticketInfoContainer">
    <h1>Tickets info for <?php echo $username ?></h1>

    <?php

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach($reservationArr as $id){
            $query = "select r.reservation_date, r.cost, r.reservation_time, m.title, s.row, s.number
                    from `reservation` r, `movie` m, `seat` s
                    where r.movieID = m.movieID and r.seatID = s.seatID and r.reservationID = $id";
            $reservations = $conn->query($query);

            $reservation = $reservations->fetch();
            if(!empty($reservation)){
                $movieTitle = $reservation['title'];
                $reserveDate = $reservation['reservation_date'];
                $reserveTime = $reservation['reservation_time'];
                $seatRow = $reservation['row'];
                $seatNumber = $reservation['number'];
                $ticketPrice = $reservation['cost'];
//                echo "<div class='ticketInfo'>";
//                echo "Movie: ".$movieTitle."<br>";
//                echo "Date: ".$reserveDate."<br>";
//                echo "Time: ".$reserveTime."<br>";
//                echo "Seat row: ".$seatRow."; seat number: ".$seatNumber."<br>";
//                echo "Ticket price: $".$ticketPrice."<br>";
//                echo "</div><br>";
                echo "<table>";
                echo "<tr class='topRow'>";
                echo "<td>";
                echo "Movie:";
                echo "</td>";
                echo "<td>";
                echo $movieTitle;
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                echo "Date:";
                echo "</td>";
                echo "<td>";
                echo $reserveDate;
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                echo "Time:";
                echo "</td>";
                echo "<td>";
                echo $reserveTime;
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                echo "Seat row: ".$seatRow;
                echo "</td>";
                echo "<td>";
                echo "seat number: ".$seatNumber;
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                echo "Ticket price:";
                echo "</td>";
                echo "<td>";
                echo "$".$ticketPrice;
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            } else{
                echo "<div style='color:red'>Failed retrieve reservation data</div>";
            }
        }

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }


    ?>

</div>

<?php include "footer.php"; ?>
</body>
</html>