<?php
session_start();
// redirect to login page if user not logged in
if (!isset($_SESSION['loggedin'])) {
  header('Location: index.html');
  exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// pass or email not stored in session - get from database instead
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// Use account ID to get account info
$stmt-> bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <link href="style.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
  <nav class="navtop">
    <div>
      <h1>CT1559 Secure Login</h1>
      <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
  </nav>
  <div class="content">
    <h2>Profile Page</h2>
    <div>
      <p>Your account details are below:</p>
      <table>
        <tr>
          <td>Username:</td>
          <td><?=$_SESSION['name']?></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><?=$password?></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><?=$email?></td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>