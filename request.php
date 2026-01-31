<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
$error = "";
$selectedCities = isset($_POST['cities']) ? $_POST['cities'] : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (count($selectedCities) !== 10) {
        $error = "Please select exactly 10 cities.";
    } else {
        $_SESSION['selected_cities'] = $selectedCities;
        header("Location: showaqi.php");
        exit();
    }
}

$conn = new mysqli("localhost", "root", "", "AQI");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT city FROM info ORDER BY city";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6fc;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
        }
        h2 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .city-list {
            columns: 2;
            column-gap: 30px;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            width: 100%;
        }
        .city-list label {
            display: block;
            margin: 8px 0;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"] {
            padding: 10px 25px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
        }
        .logout-btn input[type="submit"] {
            background-color: #dc3545;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            color: white;
        }
        .logout-btn input[type="submit"]:hover {
            background-color: #b02a37;
        }
        .city-list::-webkit-scrollbar {
            width: 6px;
        }
        .city-list::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<div class="left-top">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
</div>

<form method="post" class="logout-btn">
    <input type="submit" name="logout" value="Logout">
</form>

<h2>Select 10 Cities to View AQI</h2>

<div class="container">
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="city-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $city = htmlspecialchars($row['city']);
                    $checked = in_array($city, $selectedCities) ? "checked" : "";
                    echo "<label><input type='checkbox' name='cities[]' value='{$city}' $checked> {$city}</label>";
                }
            } else {
                echo "<p>No cities found in the database.</p>";
            }
            $conn->close();
            ?>
        </div>
        <input type="submit" name="submit" value="Show AQI">
    </form>
</div>

<?php
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}
?>

</body>
</html>
