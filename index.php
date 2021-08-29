<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

        <?php include "header.php"; ?>

        <div class="content" id="loginContainer">
            <h1>Welcome</h1>
            <p>To continue, please log in</p>
            <form method="POST">
                <table>
                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>"></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password"></td>
                    </tr>
                </table>
                <br>
                <input type="submit" value="Log in"> <input type="reset" value="Cancel">
            </form>
            <br>
            <a href="registration.php">Create new account</a>
            <br><br>
            <?php
//            session_start();

            //Success message from registration.php page, if account were registered successfully
            if(isset($_GET['success'])){
                echo "<div style='color:green'>Account created successfully</div>";
            }

            if(isset($_POST['username'])){
                if(empty($_POST['username']) && empty($POST_['password'])){
                    echo "<div style='color:red'>Please enter your username and password</div>";
                } else{
                    //connection
                    $servername = "localhost";
                    $dbusername = "root";
                    $dbpassword = "1234";
                    $db = "cinema_theatre";

                    //login
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //        echo "Connected successfully";

                        // Check if login and password are correct
                        //        $query = "SELECT * FROM `customer` WHERE username =? AND password=?";
                        //        $stmt = $conn->prepare($query);
                        //        $stmt->bindParam(1, $username);
                        //        $stmt->bindParam(2, $password);
                        //
                        //        $stmt->execute();
                        //        $res = $stmt->fetchColumn();
                        //        if ($res > 0) {
                        //            //FOUND
                        //
                        //        } else{
                        //            //NOT FOUND
                        //            echo "<div style='color:red'>Failed to log in, please try again</div>";
                        //        }

                        //        $query = "SELECT * FROM `customer` WHERE username = '$username' AND password = '$password'";
                        $query = "SELECT * FROM `customer` WHERE username = '$username'";
                        $customers = $conn->query($query);

                        $customer = $customers->fetch();
                        if(!empty($customer)){
                            $hashed_password = $customer['password'];
                            if(password_verify($password, $hashed_password)) {
                                $_SESSION['username'] = $customer['username'];
                                $_SESSION['customerID'] = $customer['customerID'];
                                header('Location: mainpage.php');
                            } else{
                                echo "<div style='color:red'>Failed to log in, wrong password</div>";
                            }
                        } else{
                            echo "<div style='color:red'>Failed to log in, username not found</div>";
                        }

                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }
                }
            }
            ?>
        </div>
        <?php include "footer.php"; ?>

    </body>


</html>