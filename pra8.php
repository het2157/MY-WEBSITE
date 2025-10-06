
<?php
$confirmation = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password'] ?? ''));

    // Validate
    if ($name && $email && $password) {
        // Store in CSV format
        $file = fopen('users.csv', 'a');
        fputcsv($file, [$name, $email, $password]);
        fclose($file);
        $confirmation = 'Registration successful! Data saved.';
    } else {
        $confirmation = 'All fields are required.';
    }
}
?>


<h2>Insert New Data</h2>
<?php if ($confirmation) echo '<p style="color:green;">' . $confirmation . '</p>'; ?>
<form method="POST" action="">
    Username: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="submit" value="Save">
</form>