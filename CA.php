<?php
// Database connection
$servername = "localhost";  // usually "localhost"
$username = "root";         // your MySQL username
$password = "";             // your MySQL password
$dbname = "wikipedia";         // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into database
if (isset($_POST['submit'])) {
    $UserName = $_POST['UserName'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];

    $sql = "INSERT INTO registor (UserName , Email , Password) VALUES ('$UserName', '$Email' , '$Password')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully!<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve data from database
$sql = "SELECT UserName, Email , Password FROM registor";
$result = $conn->query($sql);

echo "<h2>Stored Data</h2>";
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>
            <tr><th>UserName</th><th>Email</th><th>Password</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["UserName"]."</td>
                <td>".$row["Email"]."</td>
                <td>".$row["Password"]."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No data found";
}

$conn->close();
?>

<!-- HTML Form for inserting data -->
<h2>Insert New Data</h2>
<form method="POST" action="">
    Username: <input type="text" name="UserName" required><br><br>
    Email: <input type="email" name="Email" required><br><br>
    Password: <input type="password" name="Password" required><br><br>
    <input type="submit" name="submit" value="Save">
</form>

