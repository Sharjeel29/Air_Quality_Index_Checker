<?php
session_start();



if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['selected_cities'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
$cities = $_SESSION['selected_cities'];

$cookieName = 'background_color_' . $username;
$backgroundColor = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : "#ffffff";

$conn = new mysqli("localhost", "root", "", "AQI");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$placeholders = implode(',', array_fill(0, count($cities), '?'));
$query = "SELECT city, country, aqi FROM info WHERE city IN ($placeholders)";
$stmt = $conn->prepare($query);
$types = str_repeat('s', count($cities));
$stmt->bind_param($types, ...$cities);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>AQI Results</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: <?php echo htmlspecialchars($backgroundColor); ?>;
    }

    h2 {
        text-align: center;
        margin-top: 20px;
        color: #222;
    }

    .logout-btn {
        position: absolute;
        top: 15px;
        right: 20px;
    }

    .logout-btn input[type="submit"] {
        background-color: #b02a37;
        color: white;
        padding: 8px 15px;
        cursor: pointer;
        font-weight: bold;
        border: none;
        border-radius: 4px;
        font-size: 16px;
    }

   .welcome {
    position: absolute;
    top: 15px;
    left: 20px;
    font-size: 18px;
    color: #333;
    font-weight: bold;
}

    table {
        width: 85%;
        margin: 30px auto;
        border-collapse: collapse;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    thead {
        background-color:rgba(194, 119, 13, 0.95);
        color: #fff;
    }

    th, td {
        padding: 14px 16px;
        border: 1px solid #ccc;
        text-align: center;
        font-size: 15px;
    }

    tbody tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    tbody tr:hover {
        background-color: #eaf4ff;
    }
</style>

</head>
<body>

<!-- Logout Button -->
<form method="post" class="logout-btn">
    <input type="submit" name="logout" value="Logout">
</form>

<!-- Welcome Message -->
<div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</div>

<h2>Air Quality Index (AQI) for Selected Cities</h2>
<table>
    <thead>
        <tr>
            <th>City</th>
            <th>Country</th>
            <th>AQI</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                echo "<td>" . htmlspecialchars($row['country']) . "</td>";
                echo "<td>" . htmlspecialchars($row['aqi']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No data found for the selected cities.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
