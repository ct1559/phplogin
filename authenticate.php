<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

// Try and connect using variables above
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
  // If there's an error, stop the script and display error
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check data from the login form actually contains data
if (!isset($_POST['username'], $_POST['password'])) {
  exit('Please fill both the username and password fields!');
}

// Prepare SQL - Preparing SQL statement will prevent SQL injection
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
  // Bind parameters
  $stmt->bind_param('s', $_POST['username']);
  $stmt->execute();
  // Store result to check that account exists
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();
    // Account exists, so now need to verify password
    if (password_verify($_POST['password'], $password)) {
      // Verification success - user has logged in
      // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server
      session_regenerate_id();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['name'] = $_POST['username'];
      $_SESSION['id'] = $id;
      header('Location: home.php');
    } else  {
      // Password incorrect
      echo 'Incorrect username and/or password.';
    }
  } else {
    echo 'Incorrect username and/or password!';
  }

  $stmt->close();
}

?>
