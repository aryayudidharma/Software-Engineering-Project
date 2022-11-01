<?php

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    // echo "<script>alert('Already logged in as " . $_SESSION['username'] . "')</script>";
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $x = 5;
    // echo "<script>alert('" .  $email . $password . "')</script>";

    $sql = "SELECT * FROM Users WHERE user_email='$email' AND user_password='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['username'] = $row['user_username'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_role'] = $row['user_role'];

        // echo "<script>alert('".$_SESSION['user_role']."')</script>";
        header("Location: index.php");
    } else {
        echo "<script>alert('Invalid email or password!')</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    
    <link rel="stylesheet" href="./Css/nav.css">
    <link rel="stylesheet" href="./Css/login.css">
    <title>BookFit</title>
</head>

<body>
    <header>
        <nav>
            <div class="nav-logo">
                <h1><b>BookFit</b></h1>
            </div>
            <div class="nav-menu">
                <a href="./index.php" class="" style="margin-right: 100px;">Home</a>
                <?php
                if (isset($_POST['username'])) {
                ?>
                <div class="dropdown">
                    <button class="dropbtn">
                        <?php
                            echo $_POST['username'];
                        ?>
                    </button>
                </div>
                <?php
                    echo "<a href=\"./logout.php\" class=\"active\">Logout</a>";
                } else {
                    echo "<a href=\"./login.php\" class=\"active\" style:\"color: white;\">Login</a>";
                }
                ?>
            </div>
        </nav>
    </header>

    <section>
        <div class="container">
            <div class="login-title">
                <p class="login-text" style="font-size: 2rem; font-weight: 800;">Welcome back to BookFit!</p>
            </div>
            <form action="" method="POST" name="login-form" class="login-box">
                <p>E-mail</p>
                <div class="input-group">
                    <input class="tb"  type="" placeholder="" name="email" required>
                </div>
                <p>Password</p>
                <div class="input-group">
                    <input class="tb" type="password" placeholder="" name="password" required>
                </div>
                <div class="input-group">
                    <button type="submit" name="submit" value="submit" class="btn">Login</button>
                </div>
                <p class="login-register-text"><i>Don't have an account yet? </i><a id="signup-login-link" href="register.php"><u>Sign Up</u></a></p>
            </form>
        </div>
    </section>





</body>

</html>
<!DOCTYPE html>
<html lang="en"