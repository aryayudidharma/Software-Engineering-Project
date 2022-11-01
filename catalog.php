<?php
include 'config.php';

error_reporting(0);

session_start();

if (!isset($_GET["sport-id"]) || !isset($_GET["kecamatan"])) {
    // header("Location: index.php");
}

$sport_id = $_GET["sport-id"];
$kecamatan = $_GET["kecamatan"];


$sql = "SELECT v.venue_id, v.venue_name, v.venue_rate, kp.kecamatan FROM Venues v JOIN tbl_kodepos kp ON v.venue_postcode = kp.kodepos WHERE kp.kecamatan = '$kecamatan' AND v.sport_id = $sport_id";
$result = mysqli_query($conn, $sql);


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="./Css/nav.css">
    <link rel="stylesheet" href="./Css/catalog.css">
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
                            <a href="#">Profile</a>
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
        <div class="search-box">
            <form action="./catalog.php" method="get" id="search-form">
                <h3>Hi there! Welcome to Bookfit!</h3>
                <div class="input-row">
                    <div class="input-item">
                        <p>Sport</p>
                        <br>
                        <select name="sport-id">
                            <option value="v.sport_id">Any</option>
                            <?php
                            $query = "SELECT * FROM Sports";
                            $results = mysqli_query($conn, $query);
                            //loop
                            foreach ($results as $sport) {
                            ?>
                                <option value="<?php echo $sport["sport_id"]; ?>"><?php echo $sport["sport_name"]; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-item">
                        <p>Where?</p>
                        <br>
                        <select name="kecamatan">
                            <?php
                            $query = "SELECT DISTINCT kecamatan FROM tbl_kodepos;";
                            $results = mysqli_query($conn, $query);
                            //loop
                            foreach ($results as $location) {
                                $tempstr = strtolower($location["kecamatan"]);
                                $tempstr[0] = strtoupper($tempstr[0]);
                            ?>
                                <option value="<?php echo $location["kecamatan"]; ?>"><?php echo $tempstr; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="input-item">
                        <br>
                        <br>
                        <button type="submit" form="search-form">Find</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="result-wrapper">
            <h1>
                <?php

                if ($result->num_rows == 0) {
                    echo "No result found!";
                    exit();
                }


                ?>
                Showing result(s) for
                <?php
                if ($sport_id == "v.sport_id") {
                    echo " ANY SPORTS,";
                } else {
                    $sport_name = "";

                    $sql = "SELECT * FROM Sports WHERE sport_id = $sport_id";
                    $result2 = mysqli_query($conn, $sql);

                    if ($result2->num_rows > 0) {
                        $row = mysqli_fetch_assoc($result2);
                        $sport_name = $row["sport_name"];
                    }
                    echo $sport_name . ",";
                }
                ?>
                at
                <?php
                echo $kecamatan;
                ?>
            </h1>

            <div class="venue-grid">

                <?php
                foreach ($result as $venue) {
                    $venue_id = $venue['venue_id'];
                    $venue_name = $venue['venue_name'];
                    $venue_rate = $venue['venue_rate'];
                ?>

                    <div class="venue-card">
                        <div class="venue-title">
                            <h3><?php echo $venue_name ?></h3>
                        </div>
                        <br>
                        <div class="venue-info">
                            <div class="venue-rate">
                                Rating: 
                                <?php  
                                // SELECT FLOOR(AVG(rating_score)) AS venue_stars, AVG(rating_score) AS venue_avg_rating FROM VenueRatings WHERE venue_id = 1;
                                $sql_ratings = "SELECT FLOOR(AVG(rating_score)) AS venue_stars, AVG(rating_score) AS venue_avg_rating FROM VenueRatings WHERE venue_id = $venue_id;";
                                $result_ratings = mysqli_query($conn, $sql_ratings);
                                $rating_row = mysqli_fetch_assoc($result_ratings);
                                $star_count = $rating_row['venue_stars'];
                                // echo $star_count;
                                for($i = 1; $i <= 5; $i++){
                                    if($i <= $star_count){
                                        echo "<span class=\"fa fa-star\" style=\"color: orange;\"></span>";
                                    }else{
                                        echo "<span class=\"fa fa-star\"></span>";
                                    }
                                }
                                ?>
                            </div>
                            <hr>
                            <br>
                            <h3>Photos</h3>
                            <br>
                            <br>

                            <div class="venue-photos">
                                <?php
                                $sql_pictures = "SELECT * FROM VenuePictures WHERE venue_id = $venue_id LIMIT 2";
                                $result_pictures = mysqli_query($conn, $sql_pictures);

                                foreach ($result_pictures as $photo) {
                                ?>
                                    <div class="photo">
                                        <?php
                                        echo '<img src="data:image/jpeg;base64,' . base64_encode($photo['venue_picture']) . '" />';
                                        ?>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="book-form">
                            <form action="./detail.php" method="get">
                                <input type="hidden" name="venue-id" value="<?php echo $venue_id ?>">
                                <button type="submit" class="btn-book">Book Now</button>
                            </form>
                        </div>
                    </div>

                <?php
                }

                ?>
            </div>
        </div>

        <!-- <?php
                // foreach ($result as $venue) {
                //     echo "Venue ID: " . $venue['venue_id'];
                //     echo "<br>";
                //     echo "Venue Name: " . $venue['venue_name'];
                //     echo "<br>";
                //     echo "Venue Rate: " . $venue['venue_rate'];
                //     echo "<br>";
                //     echo "<br>";
                // }
                ?> -->
    </div>




</body>

</html>
<!DOCTYPE html>
<html lang="en"