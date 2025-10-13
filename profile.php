
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

// Check membership status based on expiry_date
$current_date = date('Y-m-d');
$membership_status = 'Active';
    
$membership_status = syncMemberStatus($conn, $memberId);
if ($member['expiry_date'] && $member['expiry_date'] < $current_date) {
    $membership_status = 'Expired';
    
    // ✅ AUTO-UPDATE: Sync the status column in database
    $update_status_sql = "UPDATE members SET status = 'Expired' WHERE id = $memberId AND status != 'Expired'";
    $conn->query($update_status_sql);
} else {
    // ✅ AUTO-UPDATE: Ensure status is Active if not expired
    $update_status_sql = "UPDATE members SET status = 'Active' WHERE id = $memberId AND status != 'Active'";
    $conn->query($update_status_sql);
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

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">My Profile</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Profile Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <img src="dist/img/snap.jpg" 
                                         class="img-circle elevation-2" 
                                         alt="Admin Image"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <h3 class="mt-3"><?php echo $_SESSION['fullname']; ?></h3>
                                    <p class="text-muted"><?php echo ucfirst($_SESSION['user_type']); ?></p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
                                        <p><strong>User Type:</strong> <?php echo ucfirst($_SESSION['user_type']); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                                        <p><strong>Session Active:</strong> Yes</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="settings.php" class="btn btn-primary">
                                        <i class="fas fa-cog"></i> Go to Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong> &copy; <?php echo date('Y');?> Neil</a> -</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Developed By</b> <a href="">Neil</a>
        </div>
    </footer>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>