<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    $username     = $_POST['USERname'];
    $email        = $_POST['email'];
    $dob          = $_POST['DOB'];
    $gender       = $_POST['gender'];
    $country      = $_POST['country'];
    $aqi          = $_POST['AQI'];
    $userpassword = $_POST['userpassword'];
    $color        = $_POST['color'];

    echo '<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: auto; max-width: 800px; margin: 0 auto; padding: 20px; background-color: #fdf6e3; border: 2px solid #ccc; border-radius: 10px;">';
    echo "<h1>USER DETAILS</h1>";
    echo "<table border='1' cellpadding='10' cellspacing='0' style='margin-top: 20px; background-color: #fffbe6; border-collapse: collapse;'>";
    echo "<tr style='background-color: #f5c542; color: #000;'><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>Username</td><td>$username</td></tr>";
    echo "<tr><td>Email</td><td>$email</td></tr>";
    echo "<tr><td>Date of Birth</td><td>$dob</td></tr>";
    echo "<tr><td>Gender</td><td>$gender</td></tr>";
    echo "<tr><td>Country</td><td>$country</td></tr>";
    echo "<tr><td>AQI opinion</td><td>$aqi</td></tr>";
    echo "<tr><td>Background Color (saved in Cookie)</td><td>$color</td></tr>";
    echo "</table>";

    echo '<form method="POST" action="process.php" style="margin-top: 20px;">';
    echo "<input type='hidden' name='USERname' value='$username'>";
    echo "<input type='hidden' name='email' value='$email'>";
    echo "<input type='hidden' name='DOB' value='$dob'>";
    echo "<input type='hidden' name='gender' value='$gender'>";
    echo "<input type='hidden' name='country' value='$country'>";
    echo "<input type='hidden' name='AQI' value='$aqi'>";
    echo "<input type='hidden' name='userpassword' value='$userpassword'>";
    echo "<input type='hidden' name='color' value='$color'>";
    echo "<input type='hidden' name='action' value='confirm'>";
    echo "<input type='submit' value=' Confirm' style='padding: 10px 20px; margin-right: 10px; background-color: green; color: white; border: none; border-radius: 5px;'>";
    echo "<input type='button' value='Cancel' onclick='history.back()' style='padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 5px;'>";
    echo '</form>';
    echo '</div>';
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'confirm') {
    $username     = $_POST['USERname'];
    $email        = $_POST['email'];
    $dob          = $_POST['DOB'];
    $gender       = $_POST['gender'];
    $country      = $_POST['country'];
    $aqi          = $_POST['AQI'];
    $userpassword = $_POST['userpassword'];
    $color        = $_POST['color'];

   setcookie('background_color_' . $username, $color, time() + (30 * 24 * 60 * 60), "/");x

    $con = mysqli_connect("localhost", "root", "", "aqi");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO users (username, email, dob, gender, country, aqi, userpassword)
            VALUES ('$username', '$email', '$dob', '$gender', '$country', '$aqi', '$userpassword')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['username'] = $username; 
        mysqli_close($con);
        header("Location: request.php");   
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
        mysqli_close($con);
    }
}
?>
