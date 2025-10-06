
<?php
// Backend form processor for registration
$success = false;
$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize user input
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? '';
    $confirm = $_POST["confirm"] ?? '';

    // Validate required fields
    if ($name && $email && $password && $confirm) {
        if ($password === $confirm) {
            // Store data in users.txt (never store plain passwords in real apps)
            $file = fopen("users.txt", "a");
            fwrite($file, "Username: $name, Email: $email\n");
            fclose($file);
            $success = true;
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "All fields are required.";
    }
}
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
            <td>
                <img src="https://en.wikipedia.org/static/images/icons/wikipedia.png" alt="Logo" width="50" height="50">
            </td>
            <th>
                <h1>WIKIPEDIA</h1>
            </th>
            <th></th>
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
    <h1 class="center">Create Account</h1>
    <?php if ($success): ?>
      <h2>Account Created Successfully!</h2>
      <p>Your registration has been saved.</p>
    <?php else: ?>
      <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
      <form class="form-container" method="POST" action="">
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
    <?php endif; ?>
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