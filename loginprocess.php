<?php
session_start();

if (isset($_POST["loginSubmit"])) {
    $email = $_POST["email"];
    $userpassword = $_POST["userpassword"];

    $conn = mysqli_connect('localhost', 'root', '', 'aqi');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM users WHERE email = '$email' AND userpassword = '$userpassword'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Set session variables
        $_SESSION["email"] = $row["email"];
        $_SESSION["username"] = $row["username"]; 

        echo '<div style="
            background-color: goldenrod;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 300px;
            max-width: 300px;
            margin: 0 auto;
        ">';
        echo "<h2>YOU ARE LOGGED IN!</h2>";
        echo '</div>';

        header("refresh: 2; url=request.php"); 
        exit();
    } else {
        echo '<div style="
            background-color: goldenrod;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 300px;
            max-width: 300px;
            padding: 15px;
            margin: 0 auto;
        ">';
        echo "<h2>User not found or incorrect credentials!</h2>";
        echo '</div>';

        header("refresh: 3; url=index.html"); 
        exit();
    }
} else {
    echo "Please fill in email and password.";
    header("refresh: 2; url=index.html");
    exit();
}
?>
