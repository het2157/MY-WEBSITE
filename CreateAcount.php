<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wikipedia - Create Account</title>
  <link rel="shortcut icon" href="w.png" type="image/png" />
  <link rel="stylesheet" href="createaccount.css" />

</head>


<body>
  <header>
     <table>
            <tr>
                <td>
                    <img src="https://en.wikipedia.org/static/images/icons/wikipedia.png" alt="Logo" width="50" height="50">
                </td>
                <th>
                    <h1>WIKIPEDIA</h1>
                </th>
                <th>
                    
                </th>
                <td>
                    <pre>                                                                                                                            </pre>
                </td>
            </tr>
        </table>
   
  </header>

  <nav class="navbar">
    <a href="MAIN.html">Home</a>
    <a href="ABOUT.html">About</a>
    <a href="CONTENT.html">Content</a>
    <a href="LOGIN.html">Login</a>
    <a href="CREATEACCOUNT.html" class="active">Create Account</a>
    <a href="DONATE.html">Donate</a>
  </nav>

  


  <main>
    
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


    <h1 class="center">Create Account</h1>
    <form class="form-container">
  <label for="name">Username</label>
  <input id="name" name="name" type="text" placeholder="Enter username" required />

  <label for="email">Email</label>
  <input id="email" name="email" type="email" placeholder="Enter email" required />

  <label for="password">Password</label>
  <input id="password" name="password" type="password" placeholder="Enter password" required />

  <label for="confirm">Confirm Password</label>
  <input id="confirm" name="confirm" type="password" placeholder="Confirm password" required />

  <button type="submit">Submit</button>
</form>

  </main>

  <footer>
    <div class="footer-links">
      <a href="Privacypolicy.html">Privacy Policy</a>
      <a href="ABOUT.html">About Wikipedia</a>
      <a href="DISCLAIMERS.html">Disclaimers</a>
      <a href="CONTENT.html">Content</a>
      <a href="#">Statistics</a>
    </div>
  </footer>
</body>
</html>
