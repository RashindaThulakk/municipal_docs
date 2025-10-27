<?php
require 'includes/auth.php';
require_login();    // blocks anonymous access
$page_title = "Municipal Document System";
include 'includes/header.php';
//include 'includes/header_main.php';



$role = $_SESSION['role'] ?? 'User'; // Admin, Clerk, User

// Cards definition: title, file link, description, roles allowed
$cards = [
  ['file'=>'dashboard_ba.php','title'=>'BA Dashboard','desc'=>'Building applications','icon'=>'file-earmark-text','roles'=>['Admin','Clerk','User']],
  ['file'=>'dashboard_sd.php','title'=>'SD Dashboard','desc'=>'Sub Division','icon'=>'collection','roles'=>['Admin','Clerk','User']],
  ['file'=>'assessment_dashboard.php','title'=>'Record Books','desc'=>'Assessment data','icon'=>'clipboard-data','roles'=>['Admin','Clerk','User']],
  ['file'=>'attendance_dashboard.php','title'=>'Attendance Records','desc'=>'Staff attendance','icon'=>'people','roles'=>['Admin','Clerk']],
  ['file'=>'d_finance/salary_records/dashboardSalaryRecords','title'=>'Files','desc'=>'Invoices, payments','icon'=>'cash-stack','roles'=>['Admin','Clerk']],
  ['file'=>'engineering_dashboard.php','title'=>'Personal Files','desc'=>'Engineering docs','icon'=>'gear','roles'=>['Admin','Clerk','User']],
  ['file'=>'secretary_dashboard.php','title'=>'Secretary Records','desc'=>'Meetings & minutes','icon'=>'file-text','roles'=>['Admin','Clerk','User']],

  ['file'=>'health_dashboard.php','title'=>'Health Records','desc'=>'Health related docs','icon'=>'heart-pulse','roles'=>['Admin','Clerk','User']],
  ['file'=>'user_management.php','title'=>'User Management','desc'=>'Create & manage users','icon'=>'person-gear','roles'=>['Admin']],
  ['file'=>'reports.php','title'=>'Reports','desc'=>'Export & summary reports','icon'=>'bar-chart','roles'=>['Admin','Clerk']]
];

?>

<div class="container">
  <!-- <h2 class="mb-4">Main Dashboard</h2> -->

  <div class="d-flex flex-wrap dashboard-grid">
    <?php foreach ($cards as $c): 
      if (!in_array($role, $c['roles'])) continue; // skip if role not allowed
    ?>
      <div class="col-card p-2">
        <a class="card-link" href="<?php echo $c['file']; ?>">
          <div class="card h-100">
            <div class="card-body text-center">
              <i class="bi bi-<?php echo htmlspecialchars($c['icon']); ?> mb-2"></i>
              <h5 class="card-title"><?php echo htmlspecialchars($c['title']); ?></h5>
              <p class="card-desc"><?php echo htmlspecialchars($c['desc']); ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
