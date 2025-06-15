<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($user_id);
$today_records = get_today_attendance($user_id);
$recent_activities = get_recent_activities($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Attendance System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="page-container">
            <h2 class="page-title"><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
            
            <div class="dashboard-grid">
                <div class="clock-container">
                    <div class="date-display">
                        <div class="date-label">Current Date</div>
                        <div class="date-value" id="currentDate"><?php echo date('F j, Y'); ?></div>
                    </div>
                    <div class="time" id="currentTime"></div>
                    <div class="date-display">
                        <div class="date-label">Nepal Time</div>
                        <div class="date-value">UTC+5:45</div>
                    </div>
                </div>
                
                <div class="attendance-info">
                    <div class="info-card">
                        <i class="fas fa-clock" style="color: var(--secondary);"></i>
                        <div class="info-title">Total Hours</div>
                        <div class="info-value"><?php echo $today_records['total_hours'] ?? '0.0'; ?></div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-business-time" style="color: var(--warning);"></i>
                        <div class="info-title">Overtime</div>
                        <div class="info-value"><?php echo $today_records['overtime'] ?? '0.0'; ?></div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-sign-in-alt" style="color: var(--success);"></i>
                        <div class="info-title">Check-in Time</div>
                        <div class="info-value"><?php echo $today_records['check_in'] ?? '--:--'; ?></div>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-sign-out-alt" style="color: var(--danger);"></i>
                        <div class="info-title">Check-out Time</div>
                        <div class="info-value"><?php echo $today_records['check_out'] ?? '--:--'; ?></div>
                    </div>
                </div>
                
                <div class="scanner-section">
                    <h3 class="scanner-title"><i class="fas fa-fingerprint"></i> Fingerprint Scanner</h3>
                    <p class="scanner-instruction">Place your finger on the scanner to authenticate</p>
                    
                    <div class="fingerprint-scanner" id="scanner">
                        <i class="fas fa-fingerprint fingerprint-icon"></i>
                    </div>
                    
                    <p class="scanner-instruction">Or use the button below</p>
                    <button class="scan-button" id="scanButton">
                        <i class="fas fa-fingerprint"></i> Scan Fingerprint
                    </button>
                </div>
                
                <div class="check-in-out">
                    <?php if (empty($today_records['check_in'])): ?>
                        <button class="check-btn check-in" id="checkInBtn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Check In</span>
                        </button>
                    <?php elseif (empty($today_records['check_out'])): ?>
                        <button class="check-btn check-out" id="checkOutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Check Out</span>
                        </button>
                    <?php else: ?>
                        <div class="attendance-complete">
                            <i class="fas fa-check-circle"></i>
                            <span>Attendance completed for today</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="recent-activity">
                    <h3 class="activity-title"><i class="fas fa-history"></i> Recent Activity</h3>
                    <ul class="activity-list">
                        <?php foreach ($recent_activities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-type">
                                    <?php if ($activity['activity_type'] == 'check_in'): ?>
                                        <i class="fas fa-sign-in-alt" style="color: var(--success);"></i>
                                        <span>Check In</span>
                                    <?php elseif ($activity['activity_type'] == 'check_out'): ?>
                                        <i class="fas fa-sign-out-alt" style="color: var(--danger);"></i>
                                        <span>Check Out</span>
                                    <?php else: ?>
                                        <i class="fas fa-fingerprint" style="color: var(--secondary);"></i>
                                        <span>Fingerprint Scan</span>
                                    <?php endif; ?>
                                    <span class="status-badge <?php echo $activity['status'] == 'success' ? 'status-success' : 'status-danger'; ?>">
                                        <?php echo ucfirst($activity['status']); ?>
                                    </span>
                                </div>
                                <span class="activity-time"><?php echo date('h:i A', strtotime($activity['activity_time'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/main.js"></script>
    <script src="js/fingerprint.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>