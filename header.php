<?php
session_start();
?>
<header>
    <nav class="navbar navbar-expand-md">

        <?php if(isset($_SESSION['username'])){
            echo '<a class="navbar-brand" href="mainpage.php">Cinema</a>';
        }else{
            echo '<div class="navbar-brand" id="headerTxt">Cinema</div>';
        }
        ?>

<!--        <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">-->
<!--            <span class="navbar-toggler-icon"></span>-->
<!--        </button>-->
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php if(isset($_SESSION['username'])){
                        echo '<a class="nav-link" href="logout.php">Log Out</a>';
                    } else{
                        echo '<a class="nav-link" href="index.php">Log In</a>';
                    } ?>

                </li>
                <?php if(!isset($_SESSION['username'])) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="registration.php">Registration</a>';
                    echo '</li>';
                }
                ?>

            </ul>
        </div>
    </nav>
</header>


