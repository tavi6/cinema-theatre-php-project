<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<?php
    $errorArr = array();
?>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<?php include "header.php"; ?>
<div class="content" id="loginContainer">
    <h1>Registration</h1>
    <form method="POST">
        <table id="registrationTbl">
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['username'])) {
                            if(empty($_POST['username'])){
                                array_push($errorArr, 1);
                                echo "Please enter username";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password"></td>
                <td>
                    <div class="errorMsg">
                        <?php

                        if(isset($_POST['password'])) {
                            if(empty($_POST['password'])){
                                array_push($errorArr, 1);
                                echo "Please enter password";
                            } else{
                                if (strlen($_POST["password"]) <= '8') {
                                    array_push($errorArr, 1);
                                    echo "Your Password Must Contain At Least 8 Characters!";
                                }
                                elseif(!preg_match("#[0-9]+#",$_POST["password"])) {
                                    array_push($errorArr, 1);
                                    echo "Your Password Must Contain At Least 1 Number!";
                                }
                                elseif(!preg_match("#[A-Z]+#",$_POST["password"])) {
                                    array_push($errorArr, 1);
                                    echo "Your Password Must Contain At Least 1 Capital Letter!";
                                }
                                elseif(!preg_match("#[a-z]+#",$_POST["password"])) {
                                    array_push($errorArr, 1);
                                    echo "Your Password Must Contain At Least 1 Lowercase Letter!";
                                }
                                else{
                                    if(($_POST['password'] == $_POST['confPassword']) && !empty($_POST['confPassword'])){
                                        //success
                                    } else{
                                        array_push($errorArr, 1);
                                        echo "Entered password and confirmed password are different, please try again";
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Confirm password:</td>
                <td><input type="password" name="confPassword"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['confPassword'])) {
                            if(empty($_POST['confPassword'])){
                                array_push($errorArr, 1);
                                echo "Please enter confirmed password";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" value="<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['name'])) {
                            if(empty($_POST['name'])){
                                array_push($errorArr, 1);
                                echo "Please enter name";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['email'])) {
                            if(empty($_POST['email'])){
                                array_push($errorArr, 1);
                                echo "Please enter email";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Phone number:</td>
                <td><input type="text" name="phoneNumber" value="<?php if(isset($_POST['phoneNumber'])){ echo $_POST['phoneNumber']; } ?>"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['phoneNumber'])) {
                            if(empty($_POST['phoneNumber'])){
                                array_push($errorArr, 1);
                                echo "Please enter phone number";
                            }
                            elseif(!preg_match("#[0-9]#",$_POST["phoneNumber"])) {
                                array_push($errorArr, 1);
                                echo "Only digits are allowed";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Address:</td>
                <td><input type="text" name="address" value="<?php if(isset($_POST['address'])){ echo $_POST['address']; } ?>"></td>
                <td>
                    <div class="errorMsg">
                        <?php
                        if(isset($_POST['address'])) {
                            if(empty($_POST['address'])){
                                array_push($errorArr, 1);
                                echo "Please enter address";
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
        <br>

        <input type="submit" value="Register"> <input type="reset" value="Cancel">
        <br>
    </form>
    <?php
    if(empty($errorArr) && isset($_POST['username'])){
        //connection
        $servername = "localhost";
        $dbusername = "root";
        $dbpassword = "1234";
        $db = "cinema_theatre";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$db", $dbusername, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//                echo "Connected successfully";

            // Check if username already registered
            $query = "SELECT * FROM `customer` WHERE username =?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $_POST['username']);
            $stmt->execute();
            $res = $stmt->fetchColumn();
            if ($res > 0) {
                //FOUND
                echo "<div style='color: red'>This username is already registered</div>";
            } else{
                //NOT FOUND
                // Check if email already registered
                $query = "SELECT * FROM `customer` WHERE email =?";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(1, $_POST['email']);
                $stmt->execute();
                $res = $stmt->fetchColumn();
                if ($res > 0) {
                    //FOUND
                    echo "<div style='color: red'>This email is already registered</div>";
                } else {
                    //INSERT
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $query = "INSERT INTO `customer`(`name`, `username`, `password`, `email`, `phone_number`, `address`) VALUES (?,?,?,?,?,?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(1, $_POST['name']);
                    $stmt->bindParam(2, $_POST['username']);
                    $stmt->bindParam(3, $hashed_password);
                    $stmt->bindParam(4, $_POST['email']);
                    $stmt->bindParam(5, $_POST['phoneNumber']);
                    $stmt->bindParam(6, $_POST['address']);
                    $stmt->execute();
                    $res = $stmt->fetchColumn();
                    $affected_rows = $stmt->rowCount();
                    if ($affected_rows == 1) {
                        //SUCCESS
                        header('Location: index.php?success');
                    } else{
                        //FAILURE
                        echo "<div class='errorMsg'>Failed to register an account</div>";
                    }
                }
            }


        } catch (PDOException $e) {
            echo "<div class='errorMsg'>";
            echo "Connection failed: " . $e->getMessage();
            echo "</div>";
        }
    }
    ?>
</div>
<?php include "footer.php"; ?>
</body>

</html>


