<?php
// includes/nav.php
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                <!-- User Avatar - Different for Admin vs Member -->
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
                    <!-- Member Avatar -->
                    <?php
                    $memberId = $_SESSION['user_id'];
                    $photoQuery = "SELECT photo FROM members WHERE id = $memberId";
                    $photoResult = $conn->query($photoQuery);
                    $memberPhoto = $photoResult->fetch_assoc();
                    $photoPath = (!empty($memberPhoto['photo']) && $memberPhoto['photo'] != 'default.jpg') ? 
                                'uploads/member_photos/' . $memberPhoto['photo'] : 'dist/img/snap.jpg';
                    ?>
                    <img src="<?php echo $photoPath; ?>" class="user-image img-circle elevation-2" alt="Member Avatar" 
                         style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff;">
                <?php else: ?>
                    <!-- Admin Avatar -->
                    <img src="dist/img/snap.jpg" class="user-image img-circle elevation-2" alt="Admin Avatar" 
                         style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #fff;">
                <?php endif; ?>
                
                <span class="d-none d-md-inline ml-2">
                    <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User header section -->
                <div class="dropdown-header text-center bg-lightblue">
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
                        <!-- Member Header -->
                        <?php
                        $memberId = $_SESSION['user_id'];
                        $photoQuery = "SELECT photo FROM members WHERE id = $memberId";
                        $photoResult = $conn->query($photoQuery);
                        $memberPhoto = $photoResult->fetch_assoc();
                        $photoPath = (!empty($memberPhoto['photo']) && $memberPhoto['photo'] != 'default.jpg') ? 
                                    'uploads/member_photos/' . $memberPhoto['photo'] : 'dist/img/snap.jpg';
                        ?>
                        <img src="<?php echo $photoPath; ?>" class="img-circle elevation-3" alt="Member Avatar" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #fff;">
                    <?php else: ?>
                        <!-- Admin Header -->
                        <img src="dist/img/snap.jpg" class="img-circle elevation-3" alt="Admin Avatar" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #fff;">
                    <?php endif; ?>
                    
                    <p class="mb-0 mt-2 text-white">
                        <strong><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?></strong>
                    </p>
                    <small class="text-white">
                        <?php 
                        if (isset($_SESSION['user_type'])) {
                            echo ucfirst($_SESSION['user_type']);
                        } else {
                            echo 'User';
                        }
                        ?>
                    </small>
                    
                    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member' && isset($_SESSION['membership_number'])): ?>
                        <br>
                        <small class="text-white">
                            <?php echo $_SESSION['membership_number']; ?>
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="dropdown-divider"></div>
                
                <!-- Menu items - Different for Admin vs Member -->
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'member'): ?>
                    <!-- Member Menu Items -->
                    <a href="member_profile.php" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <a href="member_dashboard.php" class="dropdown-item">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="print_membership_card.php?id=<?php echo $_SESSION['user_id']; ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-id-card mr-2"></i> Membership Card
                    </a>
                <?php else: ?>
                    <!-- Admin Menu Items -->
                    <a href="profile.php" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <a href="dashboard.php" class="dropdown-item">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="settings.php" class="dropdown-item">
                        <i class="fas fa-cogs mr-2"></i> Settings
                    </a>
                <?php endif; ?>
                
                <div class="dropdown-divider"></div>
                
                <a href="logout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>