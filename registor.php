<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "wikipedia";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Database connection failed.");
}

$errors = [];
$UserName = $Email = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors['csrf'] = "Invalid form submission. Please refresh the page and try again.";
    } else {
        $UserName = trim($_POST['UserName'] ?? "");
        $Email = trim($_POST['Email'] ?? "");
        $Password = $_POST['Password'] ?? "";
        $Confirm = $_POST['Confirm'] ?? "";

        // Validation
        if (!$UserName) $errors['UserName'] = "Please enter your username.";
        elseif (strlen($UserName) < 4) $errors['UserName'] = "Username must be at least 4 characters.";
        elseif (!preg_match('/^[a-zA-Z0-9]+$/', $UserName)) $errors['UserName'] = "Username can only contain letters and numbers.";

        if (!$Email) $errors['Email'] = "Please enter your email.";
        elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) $errors['Email'] = "Invalid email format.";

        if (!$Password) $errors['Password'] = "Please enter a password.";
        elseif (strlen($Password) < 6) $errors['Password'] = "Password must be at least 6 characters.";
        elseif (!preg_match('/[A-Z]/', $Password) || !preg_match('/[0-9]/', $Password))
            $errors['Password'] = "Password must contain at least one uppercase letter and one number.";

        if (!$Confirm) $errors['Confirm'] = "Please confirm your password.";
        elseif ($Password !== $Confirm) $errors['Confirm'] = "Passwords do not match.";

        // Check duplicates
        if (empty($errors)) {
            $stmt = $conn->prepare("SELECT 1 FROM registor WHERE Email = ? OR UserName = ?");
            $stmt->bind_param("ss", $Email, $UserName);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['duplicate'] = "Username or Email already exists!";
            }
            $stmt->close();
        }

        // Insert user if valid
        if (empty($errors)) {
            $hashed = password_hash($Password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO registor (UserName, Email, Password) VALUES (?, ?, ?)");
            if ($stmt === false) {
                $errors['db'] = "System error. Please try again later.";
            } else {
                $stmt->bind_param("sss", $UserName, $Email, $hashed);
                if ($stmt->execute()) {
                    header("Location: LOGIN.html");
                    exit;
                } else {
                    $errors['db'] = "Database error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
$conn->close();
?>
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
            <td><img src="https://en.wikipedia.org/static/images/icons/wikipedia.png" alt="Logo" width="50" height="50"></td>
            <th><h1>WIKIPEDIA</h1></th>
        </tr>
     </table>
  </header>

  <nav class="navbar">
    <a href="MAIN.html">Home</a>
    <a href="ABOUT.html">About</a>
    <a href="CONTENT.html">Content</a>
    <a href="LOGIN.html">Login</a>
    <a href="CREATEACCOUNT.php" class="active">Create Account</a>
    <a href="DONATE.html">Donate</a>
  </nav>

  <main>
    <h1 class="center">Create Account</h1>

    <?php if (!empty($errors)): ?>
      <div class="error-box" style="color:red; margin-bottom:16px;">
        <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
      </div>
    <?php endif; ?>

    <form class="form-container" method="POST" novalidate>
      <label for="UserName">Username</label>
      <input id="UserName" name="UserName" type="text" value="<?php echo htmlspecialchars($UserName); ?>" placeholder="Enter username" required />

      <label for="Email">Email</label>
      <input id="Email" name="Email" type="email" value="<?php echo htmlspecialchars($Email); ?>" placeholder="Enter email" required />

      <label for="Password">Password</label>
      <input id="Password" name="Password" type="password" placeholder="Enter password" required />

      <label for="Confirm">Confirm Password</label>
      <input id="Confirm" name="Confirm" type="password" placeholder="Confirm password" required />

      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <button type="submit">Create Account</button>
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
