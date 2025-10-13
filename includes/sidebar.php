<?php
// includes/sidebar.php
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?php echo isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member' ? 'member_dashboard.php' : 'dashboard.php'; ?>" class="brand-link">
    <?php
    $fetchLogoQuery = "SELECT logo, system_name FROM settings WHERE id = 1";
    $fetchLogoResult = $conn->query($fetchLogoQuery);
    if ($fetchLogoResult->num_rows > 0) {
        $settings = $fetchLogoResult->fetch_assoc();
        $logoPath = $settings['logo'];
        $systemName = $settings['system_name'];
    } else {
        $logoPath = 'dist/img/default-logo.png';
        $systemName = 'Core Motion System';
    }
    ?>
    <img src="<?php echo $logoPath; ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?php echo $systemName; ?></span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
          <?php
          $memberId = $_SESSION['user_id'];
          $photoQuery = "SELECT photo FROM members WHERE id = $memberId";
          $photoResult = $conn->query($photoQuery);
          $memberPhoto = $photoResult->fetch_assoc();
          $photoPath = (!empty($memberPhoto['photo']) && $memberPhoto['photo'] != 'default.jpg') ? 
                      'uploads/member_photos/' . $memberPhoto['photo'] : 'dist/img/avatar.png';
          ?>
          <img src="<?php echo $photoPath; ?>" class="img-circle elevation-2" alt="User Image">
        <?php else: ?>
          <img src="dist/img/snap.jpg" class="img-circle elevation-2" alt="User Image">
        <?php endif; ?>
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?></a>
        <small class="text-warning"><?php echo isset($_SESSION['user_type']) ? ucfirst($_SESSION['user_type']) : 'Guest'; ?></small>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
          <!-- Admin Menu -->
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="add_members.php" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>Add Members</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manage_members.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Manage Members</p>
            </a>
          </li>
          <li class="nav-item">
           
          </li>
          <li class="nav-item">
            <a href="view_type.php" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>View Membership Types</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_renewal.php" class="nav-link">
              <i class="nav-icon fas fa-sync-alt"></i>
              <p>Renew Memberships</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report.php" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Members Report</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="revenue_report.php" class="nav-link">
              <i class="nav-icon fas fa-money-bill-wave"></i>
              <p>Revenue Report</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="settings.php" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
        <?php else: ?>
          <!-- Member Menu -->
          <li class="nav-item">
            <a href="member_dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="member_profile.php" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>My Profile</p>
            </a>
          </li>
          
        <?php endif; ?>
        
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>