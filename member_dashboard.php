<?php
include('includes/config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'member') {
    header("Location: index.php");
    exit();
}

$memberId = $_SESSION['user_id'];

// Fetch member details
$memberQuery = "SELECT m.*, mt.type as membership_type_name 
                FROM members m 
                LEFT JOIN membership_types mt ON m.membership_type = mt.id 
                WHERE m.id = $memberId";
$memberResult = $conn->query($memberQuery);
$member = $memberResult->fetch_assoc();

// ✅ AUTOMATIC STATUS FOR NULL/EMPTY EXPIRY DATES
$current_date = date('Y-m-d');
$membership_status = 'Active';

if (empty($member['expiry_date']) || $member['expiry_date'] == '0000-00-00' || $member['expiry_date'] == NULL) {
    // If no expiry date is set, treat as EXPIRED
    $membership_status = 'Expired';
} else {
    // Check if expiry date is in the past
    $expiry_timestamp = strtotime($member['expiry_date']);
    $current_timestamp = strtotime($current_date);
    
    if ($expiry_timestamp !== false && $current_timestamp !== false) {
        if ($expiry_timestamp < $current_timestamp) {
            $membership_status = 'Expired';
        }
    }
}
// Calculate days remaining
$days_remaining = 0;
if ($member['expiry_date']) {
    $expiry = new DateTime($member['expiry_date']);
    $today = new DateTime();
    $interval = $today->diff($expiry);
    $days_remaining = $interval->format('%r%a');
}
?>

<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>
  <?php include('includes/sidebar.php');?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Member Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary h-100">
                    <div class="card-header">
                        <h3 class="card-title">Membership Info</h3>
                    </div>
                    <div class="card-body">
                        <strong>Membership Number:</strong>
                        <p class="text-muted"><?php echo $member['membership_number']; ?></p>
                        <strong>Membership Type:</strong>
                        <p class="text-muted"><?php echo $member['membership_type_name']; ?></p>
                        
                        <!-- ✅ CORRECT STATUS DISPLAY -->
                        <strong>Status:</strong>
                        <p class="text-muted">
                            <span class="badge badge-<?php echo $membership_status == 'Active' ? 'success' : 'danger'; ?>">
                                <?php echo $membership_status; ?>
                            </span>
                        </p>
                        
                        <?php if ($member['expiry_date']): ?>
                        <strong>Expiry Date:</strong>
                        <p class="text-muted"><?php echo date('F d, Y', strtotime($member['expiry_date'])); ?></p>
                        <strong>Days Remaining:</strong>
                        <p class="text-muted"><?php echo $days_remaining > 0 ? $days_remaining . ' days' : 'Expired'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Personal Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row h-100">
                            <div class="col-md-6">
                                <p><strong>Full Name:</strong> <?php echo $member['fullname']; ?></p>
                                <p><strong>Email:</strong> <?php echo $member['email']; ?></p>
                                <p><strong>Contact:</strong> <?php echo $member['contact_number']; ?></p>
                                <p><strong>Date of Birth:</strong> <?php echo date('F d, Y', strtotime($member['dob'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="row h-100">
                                    <div class="col-md-8">
                                        <p><strong>Address:</strong> <?php echo $member['address']; ?></p>
                                        <p><strong>Country:</strong> <?php echo $member['country']; ?></p>
                                        <p><strong>Postcode:</strong> <?php echo $member['postcode']; ?></p>
                                        <p><strong>Occupation:</strong> <?php echo $member['occupation']; ?></p>
                                    </div>
                                    <div class="col-md-4 d-flex flex-column">
                                        <?php if (!empty($member['photo']) && $member['photo'] != 'default.jpg'): ?>
                                            <div>
                                                <strong>Photo:</strong><br>
                                                <img src="uploads/member_photos/<?php echo $member['photo']; ?>" 
                                                     class="img-thumbnail mt-2" style="max-width: 100%;">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

  <footer class="main-footer">
    <strong> &copy; <?php echo date('Y');?> Neil</a> -</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Developed By</b> <a href="">Neil</a>
    </div>
  </footer>
</div>

<?php include('includes/footer.php');?>
</body>
</html>