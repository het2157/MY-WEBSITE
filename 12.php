<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "library";
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
die("Database connection failed.");
}
function generateUniqueMemberNo($conn) {
do {
$random = rand(111111, 999999);
$member_no = "LM" . $random;
$stmt = $conn->prepare("SELECT id FROM users WHERE member_no=?");
$stmt->bind_param("s", $member_no);
$stmt->execute();
$stmt->store_result();
} while($stmt->num_rows > 0);
return $member_no;
}
$errors = [];
$name = $email = $username = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
$errors['security'] = "Invalid form submission.";
} else {
$name = trim($_POST["name"] ?? "");

$email = trim($_POST["email"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";
// Server-side validation
if (!$name) $errors['name'] = "Please enter your full name.";
if (!$email) $errors['email'] = "Please enter your email address.";
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Please
enter a valid email.";
if (!$username) $errors['username'] = "Please choose a username.";
elseif (strlen($username) < 4) $errors['username'] = "Username must be at least 4
characters.";
// Password validation
if (!$password) {
$errors['password'] = "Please create a password.";
} else {
if (strlen($password) < 6) {
$errors['password'] = "Password must be at least 6 characters.";
} elseif (!preg_match('/[A-Z]/', $password)) {
$errors['password'] = "Password must include at least one uppercase letter.";
} elseif (!preg_match('/[a-z]/', $password)) {
$errors['password'] = "Password must include at least one lowercase letter.";
} elseif (!preg_match('/[0-9]/', $password)) {
$errors['password'] = "Password must include at least one number.";
} elseif (!preg_match('/[\W_]/', $password)) {
$errors['password'] = "Password must include at least one special character.";
}
}
if ($password !== $confirm_password) {
$errors['confirm_password'] = "Passwords do not match.";
}
// Check for duplicate email/username
if (empty($errors)) {
$stmt = $conn->prepare("SELECT 1 FROM users WHERE email=? OR
username=?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
ITEU204 -WDF ID No.: 24DCE007
$errors['duplicate'] = "Username or Email already exists!";
}
$stmt->close();
}
// Insert into DB
if (empty($errors)) {
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$member_no = generateUniqueMemberNo($conn);
$stmt = $conn->prepare("INSERT INTO users (name, email, username, password,
member_no) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $username, $hashed_password,
$member_no);
if ($stmt->execute()) {
header("Location: login.php");
exit;
} else {
$errors['db'] = "Database error: " . $stmt->error;
}
$stmt->close();
}
}
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register – City Central Library</title>
</head>
<body>
<div class="background-blur"></div>
<header>
<img src="logo.png" alt="Library Logo">
<h1>City Central Library</h1>
</header>
<nav>
<a href="index.php">Home</a>
<a href="register.php">Register</a>

<a href="login.php">Login</a>
<a href="books.php">Books</a>
</nav>
<main>
<h2>User Registration</h2>
<?php if(!empty($errors)): ?>
<div class="js-error">
<?php foreach($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
</div>
<?php endif; ?>
<form method="POST">
<label>Name:</label>
<input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"
required>
<label>Email:</label>
<input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"
required>
<label>Username:</label>
<input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"
required>
<label>Password:</label>
<input type="password" name="password" required>
<label>Confirm Password:</label>
<input type="password" name="confirm_password" required>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];
?>">
<button type="submit">Register</button>
</form>
</main>
<footer>
&copy; <?php echo date("Y"); ?> City Central Library. All rights reserved.
</footer>
</body>
</html>