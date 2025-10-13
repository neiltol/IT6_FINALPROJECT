<?php
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if we're viewing a specific member (admin) or own profile (member)
if (isset($_GET['id'])) {
    $memberId = $_GET['id'];
    
    // If member is trying to view someone else's profile, redirect them
    if ($_SESSION['user_type'] == 'member' && $_SESSION['user_id'] != $memberId) {
        header("Location: member_dashboard.php");
        exit();
    }
} else {
    // No ID provided - member viewing own profile
    if ($_SESSION['user_type'] == 'member') {
        $memberId = $_SESSION['user_id'];
    } else {
        // Admin without ID - redirect to members list
        header("Location: manage_members.php");
        exit();
    }
}

// Fetch member details
$memberQuery = "SELECT members.*, membership_types.type AS membership_type_name
                FROM members
                JOIN membership_types ON members.membership_type = membership_types.id
                WHERE members.id = $memberId";
$result = $conn->query($memberQuery);

if ($result->num_rows > 0) {
    $memberDetails = $result->fetch_assoc();

    // Check membership status
    $expiryDate = strtotime($memberDetails['expiry_date']);
    $currentDate = time();
    $daysDifference = floor(($expiryDate - $currentDate) / (60 * 60 * 24));
    $membershipStatus = ($daysDifference < 0) ? 'Expired' : 'Active';
} else {
    // Member not found
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: manage_members.php");
    } else {
        header("Location: member_dashboard.php");
    }
    exit();
}
?>

<?php include('includes/header.php');?>

<style>
    @media print {
        .btn, .card-tools, .breadcrumb {
            display: none;
        }
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>
  <?php include('includes/sidebar.php');?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                    Member Profile - <?php echo $memberDetails['fullname']; ?>
                <?php else: ?>
                    My Profile
                <?php endif; ?>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="manage_members.php">Manage Members</a></li>
                    <li class="breadcrumb-item active">Member Profile</li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="member_dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">My Profile</li>
                <?php endif; ?>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                    Member Information
                <?php else: ?>
                    My Information
                <?php endif; ?>
            </h3>
            <div class="card-tools">
              <a href="print_membership_card.php?id=<?php echo $memberId; ?>" target="_blank" class="btn btn-info">
                <i class="fas fa-print"></i> Print Membership Card
              </a>
              <?php if ($_SESSION['user_type'] == 'admin'): ?>
                <a href="manage_members.php" class="btn btn-default">
                  <i class="fas fa-arrow-left"></i> Back to Members
                </a>
              <?php else: ?>
                <a href="member_dashboard.php" class="btn btn-default">
                  <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Membership Number:</strong> <?php echo $memberDetails['membership_number']; ?></p>
                    <p><strong>Full Name:</strong> <?php echo $memberDetails['fullname']; ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo $memberDetails['dob']; ?></p>
                    <p><strong>Gender:</strong> <?php echo $memberDetails['gender']; ?></p>
                    <p><strong>Contact Number:</strong> <?php echo $memberDetails['contact_number']; ?></p>
                    <p><strong>Email:</strong> <?php echo $memberDetails['email']; ?></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Address:</strong> <?php echo $memberDetails['address']; ?></p>
                    <p><strong>Country:</strong> <?php echo $memberDetails['country']; ?></p>
                    <p><strong>Postcode:</strong> <?php echo $memberDetails['postcode']; ?></p>
                    <p><strong>Occupation:</strong> <?php echo $memberDetails['occupation']; ?></p>
                    <p><strong>Membership Type:</strong> <?php echo $memberDetails['membership_type_name']; ?></p>
                    <p><strong>Status:</strong> 
                      <span class="badge badge-<?php echo $membershipStatus == 'Active' ? 'success' : 'danger'; ?>">
                        <?php echo $membershipStatus; ?>
                      </span>
                    </p>
                    <?php if ($memberDetails['expiry_date']): ?>
                    <p><strong>Expiry Date:</strong> <?php echo date('M d, Y', strtotime($memberDetails['expiry_date'])); ?></p>
                    <p><strong>Days Remaining:</strong> 
                        <?php 
                        if ($daysDifference > 0) {
                            echo $daysDifference . ' days';
                        } else {
                            echo 'Expired';
                        }
                        ?>
                    </p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-center">
                <?php
                $photoPath = (!empty($memberDetails['photo']) && $memberDetails['photo'] != 'default.jpg') ? 
                            'uploads/member_photos/' . $memberDetails['photo'] : 'dist/img/avatar.png';
                ?>
                <img src="<?php echo $photoPath; ?>" class="img-thumbnail" alt="Member Photo" style="max-width: 200px; height: 200px; object-fit: cover;">
                
                <?php if ($_SESSION['user_type'] == 'member'): ?>
                  <div class="mt-3">
                 
                <?php else: ?>
                  <div class="mt-3">
                    <a href="edit_member.php?id=<?php echo $memberId; ?>" class="btn btn-primary btn-block">
                      <i class="fas fa-edit"></i> Edit Member
                    </a>
                  </div>
                <?php endif; ?>
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

<?php include('includes/footer.php');?>
</body>
</html>