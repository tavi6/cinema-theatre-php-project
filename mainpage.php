<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

    <?php include "header.php"; ?>
    <div class="content">
        <?php
//        session_start();
        date_default_timezone_set('America/Toronto');
        $username = $_SESSION['username'];
        echo "<h1> </h1>";
        $timetable = array("11:00", "13:50", "17:00", "19:40", "22:20");
        $ticketPrice = array(12, 14, 16, 16, 14);
        $dateForSQL = date("Y-m-d");
        $todaySelect = date("l M, d");
        $tomorrowSelect = date("l M, d", strtotime("+1 day"));
        $dayAfterTomorrowSelect = date("l M, d", strtotime("+2 day"));

        ?>

        <h1>Welcome <?php echo $username ?></h1>

        <?php
        if(isset($_GET['selectedDate'])){
            $selected = $_GET['selectedDate'];
            if($selected == "today"){
                $dateForSQL = date("Y-m-d");
                echo "<p>Timetable for $todaySelect</p>";
            } elseif($selected == "tomorrow"){
                $dateForSQL = date("Y-m-d", strtotime("+1 day"));
                echo "<p>Timetable for $tomorrowSelect</p>";
            } elseif($selected == "dayAfterTomorrow"){
                $dateForSQL = date("Y-m-d", strtotime("+2 day"));
                echo "<p>Timetable for $dayAfterTomorrowSelect</p>";
            }

        } else{
            $dateForSQL = date("Y-m-d");
            echo "<p>Timetable for $todaySelect</p>";
        }
        ?>

        <form>
            <select name="selectedDate">
                <option value="today"><?php echo $todaySelect ?></option>
                <option value="tomorrow"><?php echo $tomorrowSelect ?></option>
                <option value="dayAfterTomorrow"><?php echo $dayAfterTomorrowSelect ?></option>
            </select>
            <input type="submit" value="Select">
        </form>

        <?php
        //connection
        $servername = "localhost";
        $dbusername = "root";
        $dbpassword = "1234";
        $db = "cinema_theatre";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT * FROM `movie`";
            $movies = $conn->query($query);

            echo "<table id='movieTbl'>";
            $i = 0;
            foreach ($movies as $movie) {
                echo "<tr class='movieTblRow'>";
                echo "<td id='timeCell'>";
                echo "<div class='movieTime'>";
                $movieID = $movie['movieID'];
                $movieTitle = $movie['title'];
                if(isset($_GET['selectedDate'])){
                    $selected = $_GET['selectedDate'];
                    if ($selected == "today"){
                        if (time() >= strtotime($timetable[$i])) {
                            echo "<p style='color: grey'>$timetable[$i]</p>"; // grey
                        } else{
                            $urlPortion= 'movieID='.urlencode(serialize($movieID)).
                                '&movieTitle='.urlencode(serialize($movieTitle)).
                                '&reserveDate='.urlencode(serialize($dateForSQL)).
                                '&reserveTime='.urlencode(serialize($timetable[$i])).
                                '&ticketPrice='.urlencode(serialize($ticketPrice[$i]));
                            echo "<a href='chooseSeat.php?$urlPortion'><p>$timetable[$i]</p> book now</a>";
                            //echo "<a href='chooseSeat.php?movieID=$movieID&movieTitle=$movieTitle&reserveDate=$dateForSQL&reserveTime=$timetable[$i]&ticketPrice=$ticketPrice[$i]'><p>$timetable[$i]</p> book now</a>";
                        }
                    } else{
                        $urlPortion= 'movieID='.urlencode(serialize($movieID)).
                            '&movieTitle='.urlencode(serialize($movieTitle)).
                            '&reserveDate='.urlencode(serialize($dateForSQL)).
                            '&reserveTime='.urlencode(serialize($timetable[$i])).
                            '&ticketPrice='.urlencode(serialize($ticketPrice[$i]));
                        echo "<a href='chooseSeat.php?$urlPortion'><p>$timetable[$i]</p> book now</a>";
                        //echo "<a href='chooseSeat.php?movieID=$movieID&movieTitle=$movieTitle&reserveDate=$dateForSQL&reserveTime=$timetable[$i]&ticketPrice=$ticketPrice[$i]'><p>$timetable[$i]</p> book now</a>";
                    }
                } else{
                    if (time() >= strtotime($timetable[$i])) {
                        echo "<p style='color: grey'>$timetable[$i]</p>"; // grey
                    } else{
                        $urlPortion= 'movieID='.urlencode(serialize($movieID)).
                            '&movieTitle='.urlencode(serialize($movieTitle)).
                            '&reserveDate='.urlencode(serialize($dateForSQL)).
                            '&reserveTime='.urlencode(serialize($timetable[$i])).
                            '&ticketPrice='.urlencode(serialize($ticketPrice[$i]));
                        echo "<a href='chooseSeat.php?$urlPortion'><p>$timetable[$i]</p> book now</a>";
                        //echo "<a href='chooseSeat.php?movieID=$movieID&movieTitle=$movieTitle&reserveDate=$dateForSQL&reserveTime=$timetable[$i]&ticketPrice=$ticketPrice[$i]'><p>$timetable[$i]</p> book now</a>";
                    }
                }

                echo "</div>";
                echo "</td>";
                echo "<td class='posterCell'>";
                echo "<div class='posterContainer'>";
                $posterPath = $movie['posterPath'];
                echo "<img src=$posterPath width='200px'>";
                echo "</div>";
                echo "</td>";
                echo "<td class='movieInfoCell'>";
                echo "<div class='movieInfoContainer'>";
                echo "<div class='movieTitle'>";
                echo $movie["title"];
                echo "</div>";
                echo "<br>";
                echo "<div class='movieDirector'>";
                echo "by ".$movie["director"];
                echo "</div>";
                echo "<br>";
                echo "<div class='movieDescription'>";
                echo $movie["description"];
                echo "</div>";
                echo "<br>";
                echo "<div class='movieCast'>Starring: ";
                echo $movie["cast"];
                echo "</div>";
                echo "<br>";
                echo "<div class='movieDuration'>Duration: ";
                echo $movie["duration"]." min";
                echo "</div>";
                echo "<br>";
                echo "<div class='movieTrailerLink'>";
                $trailerLink = $movie['trailerLink'];
                echo "<a href=$trailerLink>Trailer</a>";
                echo "</div>";
                echo "</div>";
                echo "</td>";

                echo "</tr>";

                $i++;
            }
            echo "</table>";

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        ?>
    </div>

    <?php include "footer.php"; ?>

    </body>
</html>