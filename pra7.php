<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Wikipedia - Login</title>
  <link rel="shortcut icon" href="w.png" type="image/png" />
  <link rel="stylesheet" href="10_login.css" />
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
    <a href="2_MAIN.html">Home</a>
    <a href="3_ABOUT.html">About</a>
    <a href="4_CONTENT.html">Content</a>
    <a href="9_CREATEACCOUNT.html">CREATEACCOUNT</a>

  </nav>
  <!-- <script>                                                   PRACTICAL == 4
    

    document.addEventListener("DOMContentLoaded", function () {
      const form = document.querySelector(".login-form");
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const checkbox = document.getElementById("keep-logged");


      if (localStorage.getItem("keepLoggedIn") === "true") {
        emailInput.value = localStorage.getItem("savedEmail") || "";
        checkbox.checked = true;
      }

      form.addEventListener("submit", function (e) {
        e.preventDefault();
        const email = emailInput.value.trim();

        <?php
        session_start();
        // Hardcoded test user (replace with DB lookup in real apps)
        $test_user = [
            'email' => 'test@example.com',
            'password' => 'password123' // In real apps, use password_hash
        ];

        // Handle logout
        if (isset($_GET['logout'])) {
            session_unset();
            session_destroy();
            setcookie('rememberme', '', time() - 3600, '/');
            header('Location: pra7.php');
            exit();
        }

        // If already logged in, redirect
        if (isset($_SESSION['user'])) {
            header('Location: 2_MAIN.html');
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['keep-logged']);

            // Simple check (replace with DB lookup)
            if ($email === $test_user['email'] && $password === $test_user['password']) {
                $_SESSION['user'] = $email;
                if ($remember) {
                    setcookie('rememberme', $email, time() + 7*24*3600, '/');
                }
                header('Location: 2_MAIN.html');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <title>Wikipedia - Login</title>
          <link rel="shortcut icon" href="w.png" type="image/png" />
          <link rel="stylesheet" href="10_login.css" />
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
                <td><pre>                                                                                                                            </pre></td>
              </tr>
            </table>
          </header>
          <nav class="navbar">
            <a href="2_MAIN.html">Home</a>
            <a href="3_ABOUT.html">About</a>
            <a href="4_CONTENT.html">Content</a>
            <a href="9_CREATEACCOUNT.html">CREATEACCOUNT</a>
          </nav>
          <main class="login-section">
            <h1>Login</h1>
            <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
            <form class="login-form" action="" method="post">
              <label for="email">Email</label>
              <input id="email" name="email" type="email" placeholder="Enter email" required />
              <label for="password">Password</label>
              <input id="password" name="password" type="password" placeholder="Enter password" required />
              <div class="checkbox">
                <input id="keep-logged" name="keep-logged" type="checkbox" />
                <label for="keep-logged">Keep me logged in</label>
              </div>
              <button type="submit">Log In</button>
              <p class="forgot-link"><a href="#">Forgot your password?</a></p>
            </form>
            <p><a href="?logout=1">Logout</a></p>
          </main>
          <footer>
            <div class="footer-links">
              <a href="7_Privacypolicy.html">Privacy Policy</a>
              <a href="8_DISCLAIMERS.html">Disclaimers</a>
              <a href="3_ABOUT.html">About Wikipedia</a>
              <a href="4_CONTENT.html">Content</a>
              <a href="#">Statistics</a>
              <a href="#">Cookie Statement</a>
              <a href="#">Mobile View</a>
            </div>
          </footer>
        </body>
        </html>