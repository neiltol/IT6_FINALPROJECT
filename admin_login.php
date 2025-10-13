<?php
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $access_code = $_POST['access_code'];

        if (empty($email) || empty($password) || empty($access_code)) {
            $error_message = "All fields are required!";
        } else {
            // Verify access code first (you can store this in config or database)
            $valid_access_code = "ADMIN123"; // Change this to a secure code
            
            if ($access_code !== $valid_access_code) {
                $error_message = "Invalid access code!";
            } else {
                $hashed_password = md5($password);

                // Admin login
                $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password'";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['user_type'] = 'admin';
                    $_SESSION['fullname'] = 'Administrator';

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid admin credentials!";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Core Motion - Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page"> 
<div class="login-box">
  <div class="login-logo">
    <a href="" style="color:#dc3545;"><b>Core Motion</b> Admin</a>
  </div>
  
  <div class="card shadow-lg rounded-lg border-0">
    <div class="card-body login-card-body" style="border-radius:12px;">
      <p class="login-box-msg text-muted">Administrator Login</p>

      <?php
      if (isset($error_message)) {
          echo '<div class="alert alert-danger">' . $error_message . '</div>';
      }
      ?>

      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Admin Email" required>
          <div class="input-group-append">
            <div class="input-group-text bg-danger">
              <span class="fas fa-envelope text-white"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text bg-danger">
              <span class="fas fa-lock text-white"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" name="access_code" placeholder="Access Code" required>
          <div class="input-group-append">
            <div class="input-group-text bg-danger">
              <span class="fas fa-key text-white"></span>
            </div>
          </div>
        </div>
        
        <div class="row align-items-center">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember" class="text-muted">
                Remember Me
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-danger btn-block" 
              style="border:none; border-radius:6px;">
              Admin Login
            </button>
          </div>
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="login.php" class="text-muted">Member Login</a>
      </div>

    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<style>
  .login-page {
    background-color: #f8d7da !important;
  }
  .card {
    border-radius: 15px !important;
  }
  .login-logo a {
    font-size: 28px;
    font-weight: bold;
  }
</style>
</body>
</html>