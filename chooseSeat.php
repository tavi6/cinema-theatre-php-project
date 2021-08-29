<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<?php include "header.php"; ?>

<?php
//session_start();
$username = $_SESSION['username'];
$customerID = $_SESSION['customerID'];
//$movieID = $_GET['movieID'];
//$movieTitle = $_GET['movieTitle'];
//$reserveDate = $_GET['reserveDate'];
//$reserveTime = $_GET['reserveTime'];
//$ticketPrice = $_GET['ticketPrice'];
$movieID = unserialize($_GET['movieID']);
$movieTitle = unserialize($_GET['movieTitle']);
$reserveDate = unserialize($_GET['reserveDate']);
$reserveTime = unserialize($_GET['reserveTime']);
$ticketPrice = unserialize($_GET['ticketPrice']);

$reservedSeats = array();
$reservationArr = array();
$isInserted = false;

//connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "1234";
$db = "cinema_theatre";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $query = "SELECT * FROM `reservation` WHERE reservation_date = '$reserveDate' AND reservation_time = '$reserveTime' AND movieID='$movieID'";
    $reservations = $conn->query($query);
    foreach ($reservations as $reservation) {
        array_push($reservedSeats, $reservation['seatID']);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
<div class="content" id="seatContainer">
    <h1><?php echo $username ?>, you can choose available seats</h1>
    <table>
        <tr>
            <td>Movie:</td>
            <td><?php echo $movieTitle?></td>
        </tr>
        <tr>
            <td>Date:</td>
            <td><?php echo $reserveDate?></td>
        </tr>
        <tr>
            <td>Time:</td>
            <td><?php echo $reserveTime?></td>
        </tr>
        <tr>
            <td>Price:</td>
            <td>$<?php echo $ticketPrice?></td>
        </tr>
    </table>
    <br><br>
<!--    <p>Movie: --><?php //echo $movieTitle?><!--</p>-->
<!--    <p>Date: --><?php //echo $reserveDate ?><!--</p>-->
<!--    <p>Time: --><?php //echo $reserveTime ?><!--</p>-->
<!--    <p>Price: --><?php //echo $ticketPrice ?><!--</p>-->
    <img src="img/screen.jpg" width='500px'>
    <br><br>
    <form method="POST">
        <input type="hidden" name="isClicked">
        <table id="seatTbl">
            <tr>
                <td>row\number</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
            </tr>
            <?php
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = "SELECT * FROM `seat`";
                $seats = $conn->query($query);
                foreach ($seats as $seat) {
                    $seatID = $seat['seatID'];
                    if($seat['number'] == 1){
                        echo "<tr>";
                        echo "<td>".$seat['row']."</td>";
                    }
                    echo "<td class='cbSeatCell'>";
                    $isReserved = false;
                    foreach($reservedSeats as $reservedID){
                        if($reservedID == $seat['seatID']){
                            $isReserved = true;
                            break;
                        }
                    }
                    echo "<label>";
                    echo $seat['seatID'];
                    if($isReserved){
                        echo "<input type='checkbox' name='cbSeat[]' value='$seatID' disabled>";
                    } else{
                        echo "<input type='checkbox' name='cbSeat[]' value='$seatID'>";
                    }
                    echo "</label>";
                    echo "</td>";
                    if($seat['number'] == 6){
                        echo "</tr>";
                    }
                }

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

            ?>
        </table>
        <br>
        <input type="submit" value="Select"> <input type="reset" value="Cancel">
    </form>
    <?php

    if(isset($_POST['isClicked'])){

        if(!empty($_POST['cbSeat'])) {
            foreach($_POST['cbSeat'] as $value){

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $query = "INSERT INTO `reservation`(`movieID`, `customerID`, `seatID`, `cost`, `reservation_date`, `reservation_time`) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(1, $movieID, PDO::PARAM_INT);
                    $stmt->bindParam(2, $customerID, PDO::PARAM_INT);
                    $stmt->bindParam(3, $value, PDO::PARAM_INT);
                    $stmt->bindParam(4, $ticketPrice, PDO::PARAM_INT);
                    $stmt->bindParam(5, $reserveDate);
                    $stmt->bindParam(6, $reserveTime);
                    $stmt->execute();
                    $res = $stmt->fetchColumn();
                    $affected_rows = $stmt->rowCount();
                    if ($affected_rows == 1) {
                        //SUCCESS
                        $isInserted = true;
                        $last_id = $conn->lastInsertId();
                        array_push($reservationArr, $last_id);
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }

        }
        if($isInserted){
            $serializedArr = urlencode(serialize($reservationArr));
            echo "<script> location.replace('ticketsInfo.php?reservationArr=$serializedArr'); </script>";
        } else{
            echo "Please select available seats first";
        }

    }
    ?>
</div>

<?php include "footer.php"; ?>
</body>
</html>