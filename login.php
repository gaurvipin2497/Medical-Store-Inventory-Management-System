<?php
session_start();

if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
  include 'config.php';

  $username = $_POST['username'];
  $password = $_POST['password'];

  try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$db", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT role FROM $dbtable WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $gotCreds = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gotCreds['role'] == 'med_admin') {
      $_SESSION['med_admin'] = 'adminadmin';
      header("Location: med_admin_screen.php");
      exit;
    } else if ($gotCreds['role'] == 'receptionist') {
      $_SESSION['receptionist'] = 'receptionistic';
      header("Location: med_store_reception.php");
      exit;
    } else if ($gotCreds['role'] == 'doctor') {
      $_SESSION['doctor'] = 'doctordoctor';
      header("Location: med_store_doctor.php");
      exit;
    } else {
      header("Location: index.html");
      exit;
    }
  } catch(PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
  }
} else {
  header("Location: index.html");
  exit;
}
?>
