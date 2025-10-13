<?php
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error_message = "Email and password are required!";
        } else {
            $hashed_password = md5($password);

            // Member login - check if columns exist first
            $checkColumnsQuery = "SHOW COLUMNS FROM members LIKE 'is_active'";
            $columnsResult = $conn->query($checkColumnsQuery);
            
            if ($columnsResult->num_rows > 0) {
                // Column exists, use the full query
                $sql = "SELECT * FROM members WHERE email = '$email' AND password = '$hashed_password' AND is_active = 1";
            } else {
                // Column doesn't exist, use simplified query
                $sql = "SELECT * FROM members WHERE email = '$email' AND password = '$hashed_password'";
            }
            
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                
                // Check if membership is expired
                $current_date = date('Y-m-d');
                if ($row['expiry_date'] && $row['expiry_date'] < $current_date) {
                    $error_message = "Your membership has expired. Please contact administrator.";
                } else {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['user_type'] = 'member';
                    $_SESSION['fullname'] = $row['fullname'];
                    $_SESSION['membership_number'] = $row['membership_number'];

                    // Update last login if column exists
                    $checkLastLoginQuery = "SHOW COLUMNS FROM members LIKE 'last_login'";
                    $lastLoginResult = $conn->query($checkLastLoginQuery);
                    
                    if ($lastLoginResult->num_rows > 0) {
                        $update_sql = "UPDATE members SET last_login = NOW() WHERE id = " . $row['id'];
                        $conn->query($update_sql);
                    }

                    header("Location: member_dashboard.php");
                    exit();
                }
            } else {
                $error_message = "Invalid member credentials!";
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
  <title>Core Motion - Member Login</title>
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
    <a href="" style="color:#007bff;"><b>Core Motion</b> Member</a>
  </div>
  
  <div class="card shadow-lg rounded-lg border-0">
    <div class="card-body login-card-body" style="border-radius:12px;">
      <p class="login-box-msg text-muted">Member Login</p>

      <?php
      if (isset($error_message)) {
          echo '<div class="alert alert-danger">' . $error_message . '</div>';
      }
      ?>

      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text bg-lightblue">
              <span class="fas fa-envelope text-white"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text bg-lightblue">
              <span class="fas fa-lock text-white"></span>
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
            <button type="submit" name="login" class="btn btn-primary btn-block" 
              style="background-color:#007bff; border:none; border-radius:6px;">
              Member Login
            </button>
          </div>
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="admin_login.php" class="text-muted">Administrator Login</a>
      </div>

    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<style>
  .login-page {
    background-color: lightgoldenrodyellow !important;
  }
  .card {
    border-radius: 15px !important;
  }
  .bg-lightblue {
    background-color: #5dade2 !important;
  }
  .login-logo a {
    font-size: 28px;
    font-weight: bold;
  }
</style>
</body>
</html>