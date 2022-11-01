<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

$date = $_POST['date'];
$location = $_POST['hidden-venue-name'];
$id = $_POST['hidden-venue-id'];
$price = $_POST['hidden-venue-rate'];
$service_fee = 0.05 * $price;
if ($service_fee > 50000) {
    $service_fee = 50000;
}
$field = $_POST['field'];
$start_time =  $_POST['start-time'];
$end_time = $_POST['end-time'];

$user_id = $_SESSION['user_id'];



if (!isset($date) || !isset($field) || !isset($start_time) || !isset($end_time)) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    // header("Location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Css/nav.css">
    <link rel="stylesheet" href="./Css/book.css">
    <title>BookFit</title>
    <script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js"></script>
</head>

<body>
    <header>
        <nav>
            <div class="nav-logo">
                <h1><b>BookFit</b></h1>
            </div>
            <div class="nav-menu">
                <a href="./index.php" class="active" style="margin-right: 100px; color: white;">Home</a>
                <?php
                if (isset($_SESSION['username'])) {
                ?>
                    <div class="dropdown" style="margin-top: -1vh;">
                        <button class="dropbtn">
                            <?php
                            echo $_SESSION['username'];
                            ?>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="./profile.php">Profile</a>
                            <a href="#">My Bookings</a>
                            <a href="./logout.php" style="border-radius: 0px 0px 20px 20px; top: 0;">Logout</a>
                            <!-- <?php
                                    // echo "<a href=\"./logout.php\" class=\"\">Logout</a>";
                                    ?> -->
                        </div>
                    </div>
                <?php
                } else {
                    echo "<a href=\"./login.php\" class=\"\" >Login</a>";
                }
                ?>
            </div>
        </nav>
    </header>

    <div class="wrapper">
        <div class="container">
            <div class="receipt">
                <h1>Payment Form</h1>
                <div class="booking-data">
                    <ul>
                        <li>
                            <div class="data-name">Location</div>
                            <div class="data-info">: <?php echo $location ?></div>
                        </li>
                        <li>
                            <div class="data-name">Field No.</div>
                            <div class="data-info">: <?php echo $field ?></div>
                        </li>
                        <li>
                            <div class="data-name">Date</div>
                            <div class="data-info">: <?php echo $date ?></div>
                        </li>
                        <li>
                            <div class="data-name">Time</div>
                            <div class="data-info">: <?php echo $start_time . ":00 - " . $end_time . ":00 (" . ($end_time - $start_time) . " hr)" ?></div>
                        </li>
                        <li>
                            <div class="data-name">Time Zone</div>
                            <div class="data-info">: (GMT+7.00) Jakarta</div>
                        </li>
                        <li>
                            <div class="data-name">Price</div>
                            <div class="data-info">: Rp. <?php echo $price ?></div>
                        </li>
                        <li>
                            <div class="data-name">Service Fee</div>
                            <div class="data-info">: Rp. <?php echo $service_fee ?></div>
                        </li>
                        <li>
                            <div class="data-name">Total</div>
                            <div class="data-info">: Rp. <?php echo $price + $service_fee ?></div>
                        </li>
                        <li>
                            <div class="data-name">Payment Method</div>
                            <div class="data-info">: </div>
                            <div class="payment-methods">
                                <img id="bca" src="./Assets/Images/bca.png" alt="BCA" style="width: 55px; height: 55px; border: 1px solid yellowgreen">
                                <img id="ovo" src="./Assets/Images/ovo.png" alt="OVO" style="width: 55px; height: 55px; margin-left: 5px">
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="btn-book">
                    <button id="btn-book">Book Now</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="venue-id" value="<?php echo $id ?>">
    <input type="hidden" id="user-id" value="<?php echo $user_id ?>">
    <input type="hidden" id="field-no" value="<?php echo $field ?>">
    <input type="hidden" id="date" value="<?php echo $date ?>">
    <input type="hidden" id="start-time" value="<?php echo $start_time ?>">
    <input type="hidden" id="end-time" value="<?php echo $end_time ?>">

    <script>
        $(document).ready(function() {
            $('#btn-book').click(function(e) {
                e.preventDefault();
                $.ajax({
                        type: "POST",
                        url: "./Utils/process-booking.php",
                        cache: false,
                        data: {
                            venue_id: document.getElementById("venue-id").value,
                            user_id: document.getElementById("user-id").value,
                            field_no: document.getElementById("field-no").value,
                            date: document.getElementById("date").value,
                            start_time: document.getElementById("start-time").value,
                            end_time: document.getElementById("end-time").value
                        },
                        dataType: "html"
                    })
                    .done(function(msg) {
                        window.alert(msg);
                        window.location.href='./index.php';
                    });
            });
            $("#bca").click(function() {
                $("#bca").css('border', '1px solid yellowgreen');
                $("#ovo").css('border', 'none');
            });
            $("#ovo").click(function() {
                $("#ovo").css('border', '1px solid yellowgreen');
                $("#bca").css('border', 'none');
            });
            
        });
    </script>
</body>

</html>